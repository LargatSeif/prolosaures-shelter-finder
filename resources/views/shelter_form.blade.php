<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelter Calculator</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Shelter Calculator</h1>

        @if ($errors && $errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                        <br>
                    @endforeach
                </span>
            </div>
        @endif

        <form method="POST" action="{{ route('calculate') }}" class="space-y-4" id="shelter-form">
            @csrf
            <div id="altitude-inputs">
                <label class="block text-gray-700 text-sm font-bold mb-2">Altitudes:</label>
                <!-- Initial input -->
                <input type="number" name="altitudes[]"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline altitude-input"
                    placeholder="Altitude" 
                    @if(isset($altitudes) && count($altitudes) > 0) 
                        value="{{ $altitudes[0] }}" 
                    @endif
                    >
                    @if(isset($altitudes) && count($altitudes) > 1)
                        @for($i = 1; $i < count($altitudes); $i++)
                            <input type="number" name="altitudes[]"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline altitude-input mt-2"
                                placeholder="Altitude" value="{{ $altitudes[$i] }}">
                        @endfor
                    @endif
            </div>

            <div>
                <button type="button" id="add-altitude"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    + Add Altitude
                </button>
            </div>

            <div>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Calculate
                </button>
            </div>
        </form>

        @if (isset($shelteredArea))
            <div class="mt-6">
                <p class="text-gray-700">Sheltered Area: <span class="font-bold">{{ $shelteredArea }}</span></p>
            </div>

            <canvas id="altitudeChart" width="400" height="200"></canvas>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addAltitudeButton = document.getElementById('add-altitude');
            const altitudeInputsContainer = document.getElementById('altitude-inputs');

            addAltitudeButton.addEventListener('click', function() {
                const newInput = document.createElement('input');
                newInput.type = 'number';
                newInput.name = 'altitudes[]';
                newInput.classList.add('shadow', 'appearance-none', 'border', 'rounded', 'w-full', 'py-2',
                    'px-3', 'text-gray-700', 'leading-tight', 'focus:outline-none',
                    'focus:shadow-outline', 'altitude-input', 'mt-2'); // Added mt-2 for spacing
                newInput.placeholder = 'Altitude';

                altitudeInputsContainer.appendChild(newInput);
            });

            const form = document.getElementById('shelter-form');

            form.addEventListener('submit', function(event) {
                const altitudeInputs = document.querySelectorAll('.altitude-input');
                let isValid = true;
                
                console.log($altitudes);
                console.log($sheltered);

                altitudeInputs.forEach(input => {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 0 || value > 100000) {
                        isValid = false;
                        input.classList.add('border-red-500'); // Highlight invalid input
                    } else {
                        input.classList.remove('border-red-500'); // Remove highlight if valid
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                    alert('Please enter valid altitude values (0-100000).');
                }
            });
            
            // Chart JS
            @if (isset($shelteredArea))
                const altitudes = @json($altitudes);
                const sheltered = @json($sheltered);
                const labels = altitudes.map((altitude, index) => altitude); // Create labels
                const backgroundColors = altitudes.map((_, index) => sheltered[index] ?
                    'rgba(255, 99, 132, 0.5)' : 'rgba(54, 162, 235, 0.5)'); // Red if sheltered, blue otherwise
                const borderColors = altitudes.map((_, index) => sheltered[index] ?
                    'rgba(255, 99, 132, 1)' : 'rgba(54, 162, 235, 1)');

                    // Chart Configuration
                const chartData = {
                    labels: labels,
                    datasets: [{
                        label: 'Terrain Altitude',
                        data: altitudes,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                };
                const chartConfig = {
                    type: 'bar',
                    data: chartData,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };

                // Create Chart
                const altitudeChart = new Chart(
                    document.getElementById('altitudeChart'),
                    chartConfig
                );
                
            @endif
        });
    </script>
</html>

