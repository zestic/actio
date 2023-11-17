<?php
declare(strict_types=1);

namespace Actio\Actio\Entity;

class DataPoint
{
    private array $activity;
    private array $actor;
    private ?array $context;
    private \DateTimeImmutable $date;
    private int|string|null $id;
    private ?string $level;
    private ?string $summary;
    private array $target;

    public function getActivity(): array
    {
        return $this->activity;
    }

    public function setActivity(array $activity): DataPoint
    {
        $this->activity = $activity;

        return $this;
    }

    public function getActor(): array
    {
        return $this->actor;
    }

    public function setActor(array $actor): DataPoint
    {
        $this->actor = $actor;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(?array $context): DataPoint
    {
        $this->context = $context;

        return $this;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): DataPoint
    {
        $this->date = $date;

        return $this;
    }

    public function getId(): int|string|null
    {
        return $this->id;
    }

    public function setId(int|string|null $id): DataPoint
    {
        $this->id = $id;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): DataPoint
    {
        $this->level = $level;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): DataPoint
    {
        $this->summary = $summary;

        return $this;
    }

    public function getTarget(): array
    {
        return $this->target;
    }

    public function setTarget(array $target): DataPoint
    {
        $this->target = $target;

        return $this;
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
