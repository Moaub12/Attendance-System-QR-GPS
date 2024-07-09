<?php

namespace App\Filament\Resources\CoordinateResource\Pages;

use App\Filament\Resources\CoordinateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoordinate extends EditRecord
{
    protected static string $resource = CoordinateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
        
    }
    protected function afterSave(): void
    {
        // Retrieve the latitude and longitude data from the request data.
        $latt_long = $this->data['latt_long'];
        $latt_long = json_decode($latt_long);
        // dd($latt_long);
        // Retrieve the current zone record.
        $coordinates = $this->record;
        if ($latt_long) {
            $coordinates->update([
                'latitude' => $latt_long->lat,
                'longitude' => $latt_long->lng,
            ]);
        }
    }
}
