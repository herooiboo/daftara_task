<?php

namespace App\Infrastructure\Scramble;

use Dedoc\Scramble\Infer;
use Dedoc\Scramble\Support\Generator\TypeTransformer;
use Dedoc\Scramble\Support\OperationExtensions\ParameterExtractor\ParameterExtractor;
use Dedoc\Scramble\Support\OperationExtensions\ParameterExtractor\TypeBasedRulesDocumentationRetriever;
use Dedoc\Scramble\Support\OperationExtensions\RequestBodyExtension;
use Dedoc\Scramble\Support\OperationExtensions\RulesEvaluator\ComposedFormRequestRulesEvaluator;
use Dedoc\Scramble\Support\OperationExtensions\RulesExtractor\GeneratesParametersFromRules;
use Dedoc\Scramble\Support\OperationExtensions\RulesExtractor\ParametersExtractionResult;
use Dedoc\Scramble\Support\RouteInfo;
use Dedoc\Scramble\Support\SchemaClassDocReflector;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Reference\MethodCallReferenceType;
use Dust\Base\Controller;
use PhpParser\PrettyPrinter;
use ReflectionClass;
use ReflectionNamedType;

class DustFormRequestParametersExtractor implements ParameterExtractor
{
    use GeneratesParametersFromRules;

    public function __construct(
        private PrettyPrinter $printer,
        private TypeTransformer $openApiTransformer,
    ) {}

    public function handle(RouteInfo $routeInfo, array $parameterExtractionResults): array
    {
        if (! $routeInfo->isClassBased()) {
            return $parameterExtractionResults;
        }

        $className = $routeInfo->className();

        if (! is_a($className, Controller::class, true)) {
            return $parameterExtractionResults;
        }

        $requestClassName = $this->getFormRequestFromConstructor($className);

        if (! $requestClassName) {
            return $parameterExtractionResults;
        }

        $classReflector = Infer\Reflector\ClassReflector::make($requestClassName);

        $phpDocReflector = SchemaClassDocReflector::createFromDocString(
            $classReflector->getReflection()->getDocComment() ?: '',
        );

        $schemaName = ($phpDocReflector->getTagValue('@ignoreSchema')->value ?? null) !== null
            ? null
            : $phpDocReflector->getSchemaName($requestClassName);

        $parameterExtractionResults[] = new ParametersExtractionResult(
            parameters: $this->makeParameters(
                rules: (new ComposedFormRequestRulesEvaluator($this->printer, $classReflector, $routeInfo->method))->handle(),
                typeTransformer: $this->openApiTransformer,
                rulesDocsRetriever: new TypeBasedRulesDocumentationRetriever(
                    $routeInfo->getScope(),
                    new MethodCallReferenceType(new ObjectType($requestClassName), 'rules', []),
                ),
                in: in_array(mb_strtolower($routeInfo->method), RequestBodyExtension::HTTP_METHODS_WITHOUT_REQUEST_BODY)
                    ? 'query'
                    : 'body',
            ),
            schemaName: $schemaName,
            description: $phpDocReflector->getDescription(),
        );

        return $parameterExtractionResults;
    }

    private function getFormRequestFromConstructor(string $controllerClass): ?string
    {
        $reflection = new ReflectionClass($controllerClass);
        $constructor = $reflection->getConstructor();

        if (! $constructor) {
            return null;
        }

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (! $type instanceof ReflectionNamedType || $type->isBuiltin()) {
                continue;
            }

            $typeName = $type->getName();

            if (method_exists($typeName, 'rules')) {
                return $typeName;
            }
        }

        return null;
    }
}
