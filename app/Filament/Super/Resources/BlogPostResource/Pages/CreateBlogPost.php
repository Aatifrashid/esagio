<?php

namespace App\Filament\Super\Resources\BlogPostResource\Pages;

use App\Filament\Super\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;
}
