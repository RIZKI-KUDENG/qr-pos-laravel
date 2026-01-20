<?php

namespace App\Filament\Owner\Pages;

use BackedEnum;
use App\Models\Shift;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms; 


class CashierShift extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;
    protected static ?string $navigationLabel = 'Shift & Kasir';
    protected static ?string $title = 'Buka/Tutup Shift';
    protected  string $view = 'filament.owner.pages.cashier-shift';

    public ?array $data = []; 
    
    public ?Shift $activeShift = null;

    public function mount(): void
    {
        $this->activeShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        $this->form->fill();
    }


    public function form($form): mixed
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data'); 
    }

    protected function getFormSchema(): array
    {
        if ($this->activeShift) {
            // Form Tutup Shift
            return [
                Section::make('Tutup Shift')
                    ->schema([
                        ViewField::make('info_waktu')
                            ->label('Waktu Buka')
                            ->content($this->activeShift->start_time->format('d M Y, H:i')),
                            
                        Forms\Components\TextInput::make('actual_cash')
                            ->label('Total Uang Fisik di Laci')
                            ->prefix('Rp')
                            ->numeric()
                            ->required(),
                            
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                    ])
            ];
        }

        return [
            Section::make('Buka Shift Baru')
                ->schema([
                    Forms\Components\TextInput::make('start_cash')
                        ->label('Modal Awal (Petty Cash)')
                        ->prefix('Rp')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState(); 

        if ($this->activeShift) {
            $cashSales = 0; 
            
            $startCash = $this->activeShift->start_cash;
            $expectedCash = $startCash + $cashSales;
            $actualCash = $formData['actual_cash'];
            $difference = $actualCash - $expectedCash;

            $this->activeShift->update([
                'end_time' => now(),
                'total_cash_sales' => $cashSales,
                'expected_cash' => $expectedCash,
                'actual_cash' => $actualCash,
                'difference' => $difference,
                'status' => 'closed',
                'notes' => $formData['notes'] ?? null,
            ]);

            Notification::make()->title('Shift Ditutup')->success()->send();
            $this->redirect('/owner'); 
            
        } else {
            Shift::create([
                'tenant_id' => Auth::user()->tenant_id,
                'user_id' => Auth::id(),
                'start_time' => now(),
                'start_cash' => $formData['start_cash'],
                'status' => 'open',
            ]);

            Notification::make()->title('Shift Dibuka!')->success()->send();
            $this->redirect('/pos'); 
        }
    }
}