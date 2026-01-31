
@section('title', __('Forbidden'))

<style>
    body {
        background-color: #1a1a1a; /* Sleek Black */
        color: #ffcc00; /* Bee Yellow */
        font-family: 'Outfit', 'Nunito', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        text-align: center;
        border: 4px solid #ffcc00;
        padding: 3rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .error-code {
        font-size: 6rem;
        font-weight: 900;
        margin: 0;
        letter-spacing: -2px;
        text-transform: uppercase;
    }

    .bee-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .message {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        color: #ffffff;
    }

    .highlight {
        color: #ffcc00;
        font-weight: bold;
    }

    .btn-home {
        display: inline-block;
        background-color: #ffcc00;
        color: #1a1a1a;
        padding: 12px 30px;
        text-decoration: none;
        font-weight: bold;
        border-radius: 50px;
        transition: transform 0.2s, background-color 0.2s;
    }

    .btn-home:hover {
        background-color: #e6b800;
        transform: scale(1.05);
    }
</style>

<div class="container">
    <div class="bee-icon">🐝</div>
    <h1 class="error-code">403</h1>
    <p class="message">
        Halt! This area is <span class="highlight">O-F-F-L-I-M-I-T-S</span>.
    </p>
    <p style="color: #888; margin-bottom: 2rem;">
        You don't have the permissions to buzz around this part of the hive.
    </p>
    <a href="{{ url('/') }}" class="btn-home">Back to the Swarm</a>
</div>