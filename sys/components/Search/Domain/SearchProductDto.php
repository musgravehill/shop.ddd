<?php

declare(strict_types=1);

namespace app\components\Search\Domain;

class SearchProductDto  
{
    public function __construct(
        private readonly string $id,
        private readonly int $relevance
    ) {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRelevance()
    {
        return $this->relevance;
    }
}
