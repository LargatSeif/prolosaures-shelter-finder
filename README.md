# Prolosaure Shelter Calculator

This Laravel application calculates the sheltered area for the Prolosaure rescue problem.

## Installation

1.  Clone the repository: `git clone <repository_url>`
2.  Navigate to the project directory: `cd <project_directory>`
3.  Install dependencies: `composer install`
4.  Generate an application key: `php artisan key:generate`
5.  Configure your database in `.env` (if needed for other parts of your application).  This application itself doesn't require a database.

## Execution

### Via Artisan Command (CLI)

1.  Run the command:

    ```bash
    php artisan shelter:calculate "30 27 17 42 29 12 14 41 42 42"
    ```

    or

    ```bash
    php artisan shelter:calculate
    ```

    (and enter the altitudes when prompted).

### Via Web Interface

1.  Serve the application using Laravel's built-in server or a web server like Apache or Nginx: `php artisan serve`
2.  Open your web browser and navigate to the address shown by `php artisan serve` (usually `http://localhost:8000`).
3.  Enter the altitudes, separated by commas, in the input field and click "Calculate".
4.  The result will be displayed on the page.

## Design Choices

*   The core logic is encapsulated in the `App\Services\ShelterCalculator` class.
*   Input validation is performed in both the Artisan command and the web controller to ensure the altitudes are within the specified constraints.
*   The web interface uses a simple Blade view to display the form and the result.

## Notes

*   This implementation prioritizes clarity and simplicity.  For very large datasets, performance could be improved with more advanced algorithms, but for the problem constraints, the current approach is sufficient.
