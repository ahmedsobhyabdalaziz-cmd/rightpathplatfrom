<x-admin-layout>
    <x-slot name="header">Students</x-slot>

    <div class="mb-6">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                class="w-full max-w-md px-4 py-2 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Enrolled Courses</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($students as $student)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-slate-600">{{ substr($student->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-slate-900">{{ $student->name }}</p>
                                <p class="text-xs text-slate-500">{{ $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $student->enrollments_count }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $student->created_at->format('M j, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.students.show', $student) }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                        No students found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $students->links() }}
    </div>
</x-admin-layout>











