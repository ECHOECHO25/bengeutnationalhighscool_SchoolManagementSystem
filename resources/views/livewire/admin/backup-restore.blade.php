<div class="py-10">
    <div class="max-w-7xl mx-auto   space-y-5 ">
        <div class="bg-white rounded-2xl p-10">
            <div>
            <h1 class="text-xl font-bold text-gray700">BACK-UP DATABASE</h1>
            <x-filament::button wire:click="createBackup" color="warning" size="lg" icon="heroicon-m-inbox-stack"
                icon-position="after" class="mt-2">
                Generate Backup
            </x-filament::button>
        </div>


        <div>
            <div class="mt-10">
                <h1 class="text-xl text-gray-700 uppercase font-bold mb-4">Database Backups</h1>

                <table class="table-auto w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-2 py-1">Filename</th>
                            <th class="border border-gray-300 px-2 py-1">Size (KB)</th>
                            <th class="border border-gray-300 px-2 py-1">Last Modified</th>
                            <th class="border border-gray-300 px-2 py-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $backup)
                            <tr>
                                <td class="border border-gray-300 text-gray-700 px-2 py-1">{{ $backup['filename'] }}</td>
                                <td class="border border-gray-300 text-gray-700 px-2 py-1">{{ number_format($backup['size'] / 1024, 2) }}
                                </td>
                                <td class="border border-gray-300 text-gray-700 px-2 py-1">{{ $backup['last_modified'] }}</td>
                                <td class="border border-gray-300 text-gray-700 w-96 px-2 py-2 space-x-2">
                                    @php
                                       $name = pathinfo($backup['filename'], PATHINFO_FILENAME);
                                    @endphp
                                    <button wire:click="restoreDatabase('{{ $name }}')"
                                        class="px-2 py-1 bg-green-500 hover:bg-green-700 text-white dont-semibold rounded">Restore DB</button>
                                    <button wire:click="downloadBackup('{{ $backup['path'] }}')"
                                        class="px-2 py-1 bg-red-500 hover:bg-red-700 text-white dont-semibold rounded">Download</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-2 py-1 text-center">No backups found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        </div>
    </div>
</div>
