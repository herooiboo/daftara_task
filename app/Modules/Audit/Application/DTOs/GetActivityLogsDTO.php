<?php

namespace App\Modules\Audit\Application\DTOs;

use App\Domain\Contracts\HasPagination;
use App\Modules\Audit\Domain\Contracts\Filters\HasCauserId;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateFrom;
use App\Modules\Audit\Domain\Contracts\Filters\HasDateTo;
use App\Modules\Audit\Domain\Contracts\Filters\HasEvent;
use App\Modules\Audit\Domain\Contracts\Filters\HasSubjectType;
use Carbon\Carbon;

readonly class GetActivityLogsDTO implements HasSubjectType, HasCauserId, HasEvent, HasDateFrom, HasDateTo, HasPagination
{
    public function __construct(
        public ?string $subjectType = null,
        public ?int $causerId = null,
        public ?string $event = null,
        public ?Carbon $dateFrom = null,
        public ?Carbon $dateTo = null,
        public int     $perPage = 15,
        public int     $page = 1,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            subjectType: $data['subject_type'] ?? null,
            causerId: isset($data['causer_id']) ? (int) $data['causer_id'] : null,
            event: $data['event'] ?? null,
            dateFrom: isset($data['date_from']) ? Carbon::parse($data['date_from']) : null,
            dateTo: isset($data['date_to']) ? Carbon::parse($data['date_to']) : null,
            perPage: $data['per_page'] ?? 15,
            page: $data['page'] ?? 1,
        );
    }

    public function getSubjectType(): ?string
    {
        return $this->subjectType;
    }

    public function getCauserId(): ?int
    {
        return $this->causerId;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom?->toDateString();
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo?->toDateString();
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
