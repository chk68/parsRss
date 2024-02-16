<?php

namespace App\Console\Commands;

use App\Http\Controllers\PostController;
use Illuminate\Console\Command;

class ParseRss extends Command
{
    protected $signature = 'rss:get-parse';
    protected $description = 'Process RSS feed';

    protected PostController $postController;

    public function __construct(PostController $postController)
    {
        parent::__construct();
        $this->postController = $postController;
    }

    public function handle(): void
    {
        $this->postController->parseRss();
    }
}
