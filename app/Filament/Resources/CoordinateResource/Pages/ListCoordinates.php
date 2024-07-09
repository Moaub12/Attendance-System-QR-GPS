<?php

namespace App\Filament\Resources\CoordinateResource\Pages;

use App\Filament\Resources\CoordinateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoordinates extends ListRecords
{
    protected static string $resource = CoordinateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
}
