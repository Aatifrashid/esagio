<?php

namespace App\Filament\Super\Resources\UserResource\Pages;

use App\Filament\Super\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
