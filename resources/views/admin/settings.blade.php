@extends('layouts.admin')

@section('title', 'Pengaturan Situs')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf @method('PUT')

        @foreach ($groups as $group => $fields)
            <div class="card p-5">
                <h2 class="font-semibold mb-4">{{ $group }}</h2>
                <div class="space-y-4">
                    @foreach ($fields as $key => [$label, $type])
                        @php
                            $name = \App\Http\Controllers\Admin\SettingController::input($key);
                            $value = old($name, setting($key));
                        @endphp

                        @if ($type === 'checkbox')
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="{{ $name }}" value="1" class="rounded border-slate-300" @checked(filter_var($value, FILTER_VALIDATE_BOOLEAN))>
                                {{ $label }}
                            </label>
                        @else
                            <div>
                                <label class="label" for="{{ $name }}">{{ $label }}</label>
                                @if ($type === 'textarea' || $type === 'map')
                                    <textarea id="{{ $name }}" name="{{ $name }}" rows="3" class="input">{{ $value }}</textarea>
                                    @if ($type === 'map' && $value)
                                        <iframe src="{{ $value }}" class="mt-3 w-full h-56 rounded-lg border border-slate-200" loading="lazy" title="Pratinjau peta"></iframe>
                                    @endif
                                @elseif ($type === 'image')
                                    <div class="flex items-center gap-4">
                                        @if ($url = \App\Models\Setting::url($key))
                                            <img src="{{ $url }}" alt="" class="h-16 max-w-[160px] object-contain rounded border border-slate-200 bg-slate-50 p-1">
                                        @endif
                                        <div class="space-y-2">
                                            <input id="{{ $name }}" type="file" name="{{ $name }}" accept="image/*" class="text-sm">
                                            @if (setting($key))
                                                <label class="flex items-center gap-2 text-xs text-red-600">
                                                    <input type="checkbox" name="remove_{{ $name }}" value="1" class="rounded border-slate-300"> Hapus
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" class="input">
                                @endif
                                @error($name)<p class="error">{{ $message }}</p>@enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Pengaturan</button>
    </form>
@endsection
