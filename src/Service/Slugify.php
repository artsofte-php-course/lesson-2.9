<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Slugify {

    protected $pattern;

    protected $logger;

    public function __construct(string $pattern, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->pattern = $pattern;
    }

    public function generateSlug(string $name): string 
    {   
       $slug = strtoupper(trim(preg_replace($this->pattern, '-', $name)));  
       
       $this->logger->info(
        sprintf('Generate Slug from %s, slug = %s', $name, $slug)
       );
       
       return $slug;
    }

}