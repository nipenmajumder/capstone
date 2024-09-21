<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-2xl font-bold mb-4 text-center">Smart Waste Monitoring System</h2>
                        <div x-data="dustbinMonitor" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="(bin, index) in bins" :key="index">
                                <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-700" x-text="bin.name"></h3>
                                        <p class="text-sm text-gray-500"
                                           x-text="`Updated at: ${new Date(bin.updated_at.date).toLocaleString()}`"></p>
                                    </div>
                                    <p class="text-gray-800 mb-2">
                                        Fill Level: <span class="font-bold text-green-600"
                                                          x-text="`${bin.fill_level}%`"></span>
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        Location: <span class="text-blue-500"
                                                        x-text="`${bin.latitude}, ${bin.longitude}`"></span>
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                                        <div class="bg-green-500 h-2.5 rounded-full"
                                             :style="{ width: `${bin.fill_level}%` }"></div>
                                    </div>
                                    <div class="mt-4">
                                        <a :href="`https://www.google.com/maps?q=${bin.latitude},${bin.longitude}`"
                                           target="_blank"
                                           class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                            View on Google Maps
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dustbinMonitor', () => ({
                bins: [], // Initialize the bins array

                init() {
                    // Listen for Laravel Echo events using Reverb WebSockets
                    Echo.channel('dustbin-updates')
                        .listen('DustbinDataRetrieved', (e) => {
                            console.log(e.data); // Handle the data in the browser console
                            this.updateBins(e.data); // Call the function to update bins
                        });
                },

                updateBins(data) {
                    // Update the Alpine.js bins array with new data
                    this.bins = data;
                }
            }));
        });
    </script>

</x-app-layout>
