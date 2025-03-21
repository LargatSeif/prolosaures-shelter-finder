<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelter Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 h-screen flex">
    <div class="w-1/2 p-8">
        <div class="bg-white p-8 rounded shadow-md">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">Shelter Calculator</h1>

            @if ($errors && $errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
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
                    <div class="flex items-center mb-2">
                        <input type="number" name="altitudes[]"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline altitude-input"
                            placeholder="Altitude" @if (isset($altitudes) && count($altitudes) > 0) value="{{ $altitudes[0] }}" @endif>
                        <button type="button"
                            class="delete-altitude ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded focus:outline-none focus:shadow-outline">
                            &times;
                        </button>
                    </div>
                    @if (isset($altitudes))
                        @for ($i = 1; $i < count($altitudes); $i++)
                            <div class="flex items-center mb-2">
                                <input type="number" name="altitudes[]"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline altitude-input"
                                    placeholder="Altitude" value="{{ $altitudes[$i] }}">
                                <button type="button"
                                    class="delete-altitude ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded focus:outline-none focus:shadow-outline">
                                    &times;
                                </button>
                            </div>
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
        </div>
    </div>

    <div class="w-1/2 p-8">
        @if (isset($shelteredArea))
            <div class="bg-white p-8 rounded shadow-md">
                <p class="text-gray-700">Sheltered Area: <span class="font-bold">{{ $shelteredArea }}</span></p>
                <canvas id="altitudeChart" width="400" height="200"></canvas>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addAltitudeButton = document.getElementById('add-altitude');
            const altitudeInputsContainer = document.getElementById('altitude-inputs');

            // Function to add delete button functionality
            function setupDeleteButtons() {
                document.querySelectorAll('.delete-altitude').forEach(button => {
                    button.addEventListener('click', function() {
                        // Only delete if there's more than one altitude input
                        if (document.querySelectorAll('.altitude-input').length > 1) {
                            this.parentElement.remove();
                        } else {
                            alert('You need at least one altitude input.');
                        }
                    });
                });
            }

            // Setup delete buttons for initial elements
            setupDeleteButtons();

            addAltitudeButton.addEventListener('click', function() {
                const inputContainer = document.createElement('div');
                inputContainer.classList.add('flex', 'items-center', 'mb-2');

                const newInput = document.createElement('input');
                newInput.type = 'number';
                newInput.name = 'altitudes[]';
                newInput.classList.add('shadow', 'appearance-none', 'border', 'rounded', 'w-full', 'py-2',
                    'px-3', 'text-gray-700', 'leading-tight', 'focus:outline-none',
                    'focus:shadow-outline', 'altitude-input');
                newInput.placeholder = 'Altitude';

                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.classList.add('delete-altitude', 'ml-2', 'bg-red-500', 'hover:bg-red-700',
                    'text-white',
                    'font-bold', 'py-2', 'px-3', 'rounded', 'focus:outline-none', 'focus:shadow-outline'
                );
                deleteButton.innerHTML = '&times;';
                deleteButton.addEventListener('click', function() {
                    if (document.querySelectorAll('.altitude-input').length > 1) {
                        inputContainer.remove();
                    } else {
                        alert('You need at least one altitude input.');
                    }
                });

                inputContainer.appendChild(newInput);
                inputContainer.appendChild(deleteButton);
                altitudeInputsContainer.appendChild(inputContainer);
            });

            const form = document.getElementById('shelter-form');

            form.addEventListener('submit', function(event) {
                const altitudeInputs = document.querySelectorAll('.altitude-input');
                let isValid = true;

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

            @if (isset($shelteredArea))
                // Chart Data
                const altitudes = @json($altitudes);
                const sheltered = @json($sheltered); // Get the sheltered array from the controller
                const labels = altitudes.map((altitude, index) => altitude); // Create labels
                const backgroundColors = altitudes.map((_, index) => sheltered[index] ?
                    'rgba(255, 99, 132, 0.5)' : 'rgba(54, 162, 235, 0.5)'); // Red if sheltered, blue otherwise
                const borderColors = altitudes.map((_, index) => sheltered[index] ?
                    'rgba(255, 99, 132, 1)' : 'rgba(54, 162, 235, 1)');

                // Chart Configuration
                const chartData = {
                    labels: labels,
                    datasets: [{
                        data: altitudes,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                    }]
                };

                const chartConfig = {
                    type: 'bar',
                    data: chartData,
                    options: {
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                align: 'center',
                                labels:{
                                    generateLabels:()=>{
                                        return [
                                            {
                                                text: 'Sheltered',
                                                fillStyle: 'rgba(255, 99, 132, 0.5)'
                                            },
                                            {
                                                text: 'Unsheltered',
                                                fillStyle: 'rgba(54, 162, 235, 0.5)'
                                            }
                                        ];
                                    }
                                }
                            },
                            tooltip:{
                                callbacks:{
                                    label: function(context){
                                        const index = context.dataIndex;
                                        return sheltered[index]? 'Sheltered' : 'Unsheltered';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Sheltered Area'
                                }
                            },
                            x: {
                                position: 'top',
                                title: {
                                    display: true,
                                    text: 'Altitude'
                                }
                            }
                        }
                    },

                };

                // Create Chart
                const altitudeChart = new Chart(
                    document.getElementById('altitudeChart'),
                    chartConfig
                );
            @endif
        });
    </script>
</body>

</html>
