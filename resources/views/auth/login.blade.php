<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <style>
      body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Roboto", sans-serif;
        overflow: hidden;
      }
      .login-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 2.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        max-width: 420px;
        width: 100%;
        animation: slideIn 0.6s ease-out;
        position: relative;
      }
      .login-container::before {
        content: "";
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
        border-radius: 1.5rem;
        z-index: -1;
        opacity: 0.5;
      }
      @keyframes slideIn {
        from {
          opacity: 0;
          transform: translateY(30px) scale(0.9);
        }
        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }
      .input-field {
        transition: all 0.3s ease;
        border: 2px solid transparent;
      }
      .input-field:focus {
        transform: scale(1.02);
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        border-color: #667eea;
      }
      .input-group {
        position: relative;
      }
      .input-group i {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
      }
      .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
      }
      .logo {
        font-size: 2.5rem;
        font-weight: bold;
        color: #667eea;
        text-align: center;
        margin-bottom: 0.25rem;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .login-description {
        text-align: center;
        color: #555;
        font-size: 1rem;
        margin-bottom: 1.75rem;
        font-weight: 500;
      }
      .btn-login {
        background: linear-gradient(135deg, #667eea, #764ba2);
        transition: all 0.3s ease;
      }
      .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      }
      .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
      }
      .particle {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s infinite linear;
      }
      @keyframes float {
        from {
          transform: translateY(100vh) rotate(0deg);
        }
        to {
          transform: translateY(-100px) rotate(360deg);
        }
      }
      .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #667eea;
      }
    </style>
  </head>
  <body>
    <div class="particles" id="particles"></div>
    <div class="login-container">
      <div class="logo">
        <i class="fas fa-ticket-alt mr-2"></i>Antrian Digital
      </div>
      <div class="login-description">
        Silakan masuk menggunakan email dan password Anda untuk mengakses sistem antrian digital.
      </div>
      <form id="loginForm" action="{{ route('login') }}" method="POST">
    @csrf
    <div class="input-group mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            class="input-field mt-1 block w-full px-3 py-3 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter your email"
            required
            value="{{ old('email') }}"
        />
        <i class="fas fa-envelope"></i>
        @error('email')
            <div class="error-message" style="display: block;">{{ $message }}</div>
        @enderror
    </div>
    <div class="input-group mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            class="input-field mt-1 block w-full px-3 py-3 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter your password"
            required
        />
        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        @error('password')
            <div class="error-message" style="display: block;">{{ $message }}</div>
        @enderror
    </div>
    <button
        type="submit"
        class="btn-login w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out"
    >
        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
    </button>
</form>
      <div
        class="mt-6 text-center text-gray-500 text-xs"
      >
        <i class="fab fa-facebook-f mr-2"></i>
        <i class="fab fa-twitter mr-2"></i>
        <i class="fab fa-google mr-2"></i>
        Or sign in with social media
      </div>
    </div>

    <script>
      // Create floating particles
      const particles = document.getElementById("particles");
      for (let i = 0; i < 20; i++) {
        const particle = document.createElement("div");
        particle.className = "particle";
        particle.style.width = Math.random() * 10 + 5 + "px";
        particle.style.height = particle.style.width;
        particle.style.left = Math.random() * 100 + "vw";
        particle.style.animationDelay = Math.random() * 6 + "s";
        particle.style.animationDuration = Math.random() * 4 + 6 + "s";
        particles.appendChild(particle);
      }

      // Toggle password visibility
      document
        .getElementById("togglePassword")
        .addEventListener("click", function () {
          const password = document.getElementById("password");
          const icon = this;
          if (password.type === "password") {
            password.type = "text";
            icon.className = "fas fa-eye-slash toggle-password";
          } else {
            password.type = "password";
            icon.className = "fas fa-eye toggle-password";
          }
        });

      
    </script>
  </body>
</html>
