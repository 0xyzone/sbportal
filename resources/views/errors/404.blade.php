

@section('title', __('Word Not Found'))

<style>
    body {
        background-color: #1a1a1a; /* Black */
        color: #ffcc00; /* Bee Yellow */
        font-family: 'Outfit', 'Nunito', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
        overflow: hidden;
    }

    /* Background Pattern */
    body::before {
        content: "";
        position: absolute;
        width: 200%;
        height: 200%;
        background-image: radial-gradient(#333 10%, transparent 10%);
        background-size: 30px 30px;
        opacity: 0.3;
        z-index: -1;
    }

    .container {
        text-align: center;
        max-width: 600px;
    }

    .error-code {
        font-size: 8rem;
        font-weight: 900;
        margin: 0;
        line-height: 1;
        text-shadow: 4px 4px 0px #000, 8px 8px 0px #444;
    }

    .search-icon {
        font-size: 5rem;
        display: block;
        margin-bottom: 1rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .message {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 1rem 0;
        color: #ffffff;
    }

    .sub-message {
        font-size: 1.1rem;
        color: #bbbbbb;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .dictionary-box {
        background: #252525;
        border-left: 5px solid #ffcc00;
        padding: 15px;
        text-align: left;
        margin-bottom: 2rem;
        font-style: italic;
    }

    .btn-return {
        display: inline-block;
        background-color: #ffcc00;
        color: #1a1a1a;
        padding: 15px 40px;
        text-decoration: none;
        font-weight: 800;
        border-radius: 4px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .btn-return:hover {
        background-color: #ffffff;
        box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
    }
</style>

<div class="container">
    <span class="search-icon">🔍🐝</span>
    <h1 class="error-code">404</h1>
    <p class="message">U-N-K-N-O-W-N</p>
    
    <div class="dictionary-box">
        <strong>404</strong> /noun/ <br>
        A page or word that does not exist in our hive. Likely a typo or a broken flight path.
    </div>

    <p class="sub-message">
        We couldn't find the page you were looking for. <br>
        Double-check your spelling and try again!
    </p>

    <a href="{{ url('/') }}" class="btn-return">Return to Competition</a>
</div>