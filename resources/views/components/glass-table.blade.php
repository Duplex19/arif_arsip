@props(['headers' => [], 'rows' => [], 'actions' => false])

<div {{ $attributes->merge(['class' => 'glass-table w-full']) }}>
    <table class="w-full text-sm text-left text-slate-300">
        <thead>
            <tr class="text-slate-400 uppercase">
                @foreach($headers as $header)
                    <th scope="col">{{ $header }}</th>
                @endforeach
                @if($actions)
                    <th scope="col">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
