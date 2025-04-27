<x-app-layout>
    <!-- Page header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointments') }}
        </h2>
    </x-slot>

    <!-- Main content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success message -->
            @if(session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Appointments table -->
            <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date &amp; Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4">{{ $appointment->patient->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $appointment->doctor->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 capitalize">{{ $appointment->status }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                       class="inline-block rounded bg-yellow-500 px-2 py-1 text-black hover:bg-yellow-600">
                                        Reschedule
                                    </a>
                                    <form action="{{ route('appointments.destroy', $appointment) }}"
                                          method="POST" class="inline-block ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded bg-red-500 px-2 py-1 text-black hover:bg-red-600">
                                            Cancel Appointment
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No appointments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
