<?php
declare(strict_types=1);

namespace Actio\Actio\Entity;

class DataPoint
{
    private array $activity;
    private array $actor;
    private ?array $context;
    private \DateTimeImmutable $date;
    private int|string $id;
    private ?string $level;
    private ?string $summary;
    private array $target;

    public function getActivity(): array
    {
        return $this->activity;
    }

    public function setActivity(array $activity): void
    {
        $this->activity = $activity;
    }

    public function getActor(): array
    {
        return $this->actor;
    }

    public function setActor(array $actor): void
    {
        $this->actor = $actor;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(?array $context): void
    {
        $this->context = $context;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getId(): int|string
    {
        return $this->id;
    }

    public function setId(int|string $id): void
    {
        $this->id = $id;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): void
    {
        $this->level = $level;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    public function getTarget(): array
    {
        return $this->target;
    }

    public function setTarget(array $target): void
    {
        $this->target = $target;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        return [
            'activity' => $this->activity,
            'actor'    => $this->actor,
            'context'  => $this->context,
            'date'     => $this->date->format('Y-m-d\TH:i:s\Z'),
            'id'       => $this->id,
            'level'    => $this->level,
            'summary'  => $this->summary,
            'target'   => $this->target,
        ];
    }
}
