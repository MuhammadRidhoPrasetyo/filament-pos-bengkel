<?php

namespace App\Filament\Clusters\Services\Resources\ServiceOrders\Pages;

use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Models\ServiceOrder;
use App\Traits\HasDocumentNumber;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\Services\Resources\ServiceOrders\ServiceOrderResource;

class CreateServiceOrder extends CreateRecord
{
    use HasDocumentNumber;
    protected static string $resource = ServiceOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['number'] = $this->generateDocumentNumber('SRV', storeId: $data['store_id']);

        return $data;
    }

    protected function afterCreate(): void
    {

        $data = $this->form->getState();

        $record = $this->record;

        if (!empty($record->customer_id)) {
            $supplier = Supplier::find($record->customer_id);

            $record->customerSnapshot()->create([
                'customer_id' => $supplier->id,
                'name'        => $supplier->name,
                'phone'       => $supplier->phone,
                'address'     => $supplier->address,
            ]);
        } else {
            // Jika pelanggan baru (manual)
            $record->customerSnapshot()->create([
                'name'    => $data['name'],
                'phone'   => $data['phone'],
                'address' => $data['address'],
            ]);
        }
    }
}
