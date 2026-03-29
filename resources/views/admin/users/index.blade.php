<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Users') }}
            </h2>
            @if(auth()->user()->role === 'administrator' || !in_array(request('role'), ['admin', 'administrator']))
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create New User</a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 border-b">ID</th>
                            <th class="p-4 border-b">Name</th>
                            <th class="p-4 border-b">Email</th>
                            <th class="p-4 border-b">Role</th>
                            <th class="p-4 border-b">Status</th>
                            @if(request('role') === 'organizer')
                                <th class="p-4 border-b">Artist/Band Name</th>
                            @endif
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="p-4 border-b">{{ $user->id }}</td>
                                <td class="p-4 border-b">{{ $user->name }}</td>
                                <td class="p-4 border-b">{{ $user->email }}</td>
                                <td class="p-4 border-b capitalize">{{ $user->role }}</td>
                                <td class="p-4 border-b">
                                    @if($user->trashed())
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Deactivated</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Active</span>
                                    @endif
                                </td>
                                @if(request('role') === 'organizer')
                                    <td class="p-4 border-b">{{ $user->artist_name ?? '-' }}</td>
                                @endif
                                <td class="p-4 border-b text-right">
                                    @if(auth()->user()->role !== 'administrator' && in_array($user->role, ['admin', 'administrator']))
                                        <span class="text-gray-400 italic text-sm">Locked</span>
                                    @else
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                        
                                        @if($user->trashed())
                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to activate this user?');">
                                                @csrf
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-900 font-semibold">Activate</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Deactivate</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
