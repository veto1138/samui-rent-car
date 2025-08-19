<?php

namespace App\Livewire;

use App\Models\Rental;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RentalTable extends DataTableComponent
{
    protected $model = Rental::class;
    
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setTableAttributes([
            'class' => 'min-w-full divide-y divide-gray-200',
        ]);
        $this->setSearchStatus(true);
        $this->setSearchDebounce(300);
        $this->setPaginationStatus(true);
        $this->setPaginationTheme('tailwind');
        $this->setPerPage(10);
    }

    public function columns(): array
    {
        return [
            Column::make('สถานที่คืนรถ', 'end_location')
                ->sortable()
                ->searchable(),
            
            Column::make('จำนวนวันที่เช่า', 'rental_days')
                ->sortable()
                ->format(function ($value, $column, $row) {
                    $startDate = Carbon::parse($row->start_date);
                    $endDate = Carbon::parse($row->end_date);
                    return $startDate->diffInDays($endDate);
                }),
            
            Column::make('พนักงาน', 'employee')
                ->sortable()
                ->format(function ($value, $column, $row) {
                    return 'ป้อ'; // ตัวอย่างข้อมูล
                }),
            
            Column::make('วันที่ลงข้อมูล', 'created_at')
                ->sortable()
                ->format(function ($value, $column, $row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i');
                }),
            
            Column::make('ไฟล์', 'files')
                ->format(function ($value, $column, $row) {
                    $files = [];
                    if ($row->selfie_image) {
                        $files[] = '<a href="' . asset('storage/' . $row->selfie_image) . '" target="_blank" class="text-blue-600 hover:text-blue-800">รูป1</a>';
                    }
                    if ($row->national_id_image) {
                        $files[] = '<a href="' . asset('storage/' . $row->national_id_image) . '" target="_blank" class="text-blue-600 hover:text-blue-800">รูป2</a>';
                    }
                    if ($row->driver_license_image) {
                        $files[] = '<a href="' . asset('storage/' . $row->driver_license_image) . '" target="_blank" class="text-blue-600 hover:text-blue-800">PDF</a>';
                    }
                    return implode(', ', $files);
                })
                ->html(),
            
            Column::make('สถานะ', 'status')
                ->sortable()
                ->format(function ($value, $column, $row) {
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'using' => 'bg-blue-100 text-blue-800',
                        'success' => 'bg-green-100 text-green-800',
                        'cancel' => 'bg-red-100 text-red-800',
                    ];
                    
                    $statusLabels = [
                        'pending' => 'รอดำเนินการ',
                        'using' => 'กำลังใช้บริการ',
                        'success' => 'สำเร็จ',
                        'cancel' => 'ยกเลิก',
                    ];
                    
                    $color = $statusColors[$row->status] ?? 'bg-gray-100 text-gray-800';
                    $label = $statusLabels[$row->status] ?? $row->status;
                    
                    return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $color . '">' . $label . '</span>';
                })
                ->html(),
            
            Column::make('สัญญาเช่า', 'agreement')
                ->format(function ($value, $column, $row) {
                    return '<a href="#" class="text-blue-600 hover:text-blue-800">พิมพ์</a>';
                })
                ->html(),
            
            ButtonGroupColumn::make('จัดการ')
                ->buttons([
                    ButtonColumn::make('แก้ไข')
                        ->title(fn ($row) => 'แก้ไข')
                        ->class('bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm')
                        ->attributes([
                            'onclick' => 'window.location.href = \'' . route('rentals.edit', ':id') . '\'.replace(\':id\', ' . $row->id . ')',
                        ]),
                    ButtonColumn::make('ลบ')
                        ->title(fn ($row) => 'ลบ')
                        ->class('bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm')
                        ->attributes([
                            'wire:click' => 'delete(' . $row->id . ')',
                            'wire:confirm' => 'คุณต้องการลบรายการนี้หรือไม่?',
                        ]),
                ]),
        ];
    }

    public function builder(): Builder
    {
        $query = Rental::query();
        
        if ($this->getSearch()) {
            $search = $this->getSearch();
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhere('national_id', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('start_location', 'like', '%' . $search . '%')
                  ->orWhere('end_location', 'like', '%' . $search . '%');
            });
        }
        
        return $query;
    }

    public function delete($id)
    {
        try {
            $rental = Rental::findOrFail($id);
            
            // ลบไฟล์รูปภาพ
            if ($rental->selfie_image) {
                \Storage::disk('public')->delete($rental->selfie_image);
            }
            if ($rental->national_id_image) {
                \Storage::disk('public')->delete($rental->national_id_image);
            }
            if ($rental->driver_license_image) {
                \Storage::disk('public')->delete($rental->driver_license_image);
            }

            $rental->delete();
            
            $this->dispatch('show-message', [
                'type' => 'success',
                'message' => 'ข้อมูลการเช่ารถถูกลบเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'type' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()
            ]);
        }
    }
}
