<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-7xl mx-auto">
                        <div class="flex justify-between">
                            <div>
                                <h1 class="text-2xl font-semibold">User</h1>
                            </div>
                            <div>
                                <a href="{{ route('user.create') }}"
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</a>
                            </div>
                        </div>
                        <table class="w-full mt-5">
                            <thead>
                            <tr class="bg-gray-50">
                                <th class="py-2">No</th>
                                <th class="py-2">Name
                                <th class="py-2">Email</th>
                                <th class="py-2">Role</th>
                                <th class="py-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td class="border px-4 py-2">{{ $key + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $user->name }}</td>
                                    <td class="border px-4 py-2">{{ $user->email }}</td>
                                    <td class="border px-4 py-2">{{ collect($user->roles)->pluck('name')->implode(',') }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
