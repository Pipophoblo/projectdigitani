<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IPB Digitani</title>
  <style>
    /* ... Insert your entire login.css content here ... */
    /* For brevity, I’ll summarize. Paste the full CSS from login.css */
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'poppins', sans-serif;
  }
  
  body, html {
    height: 100%;
  }
  
  .background {
    background: url('ASSETS/REKTORAT-IPB.jpeg') no-repeat center center/cover;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  .login-box {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 10px;
    width: 350px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
  }
  
  .logo {/* image 11 */
    height: 60px;
    left: 100px;
    width: 200px;
    margin-bottom: 30px;
  }
  
  h2 {
    color: #1a237e;
    margin: 5px 0;
  }
  
  .subtitle {
    font-size: 14px;
    color: #333;
    margin-bottom: 20px;
  }
  
  form h3 {
    color: #1a237e;
    text-align: left;
    margin-bottom: 15px;
  }
  
  input[type="text"],input[type="email"], input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: none;
    border-radius: 5px;
    background: #f0f0f0;
  }

  input.is-invalid {
  border: 1px solid red;
  background-color: #ffe6e6;
}
  .options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    margin-bottom: 15px;
  }
  
  .options a {
    text-decoration: none;
    color: #1a237e;
  }
  
  button {
    width: 100%;
    padding: 10px;
    background-color: #0d47a1;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #1565c0;
  }
  
  .register {
    margin-top: 10px;
    font-size: 13px;
  }
  
  .register a {
    text-decoration: none;
    color: #0d47a1;
    font-weight: bold;
  }
  
  footer {
    margin-top: 15px;
    text-align: center;
    font-size: 12px;
    color: white;
  }
  </style>
</head>
<body>
  <div class="background">
    <div class="login-box">
      <img src="{{ asset('ASSETS/image 11.png') }}" alt="Logo Digitani" class="logo" />

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <h3>Masuk</h3>

        <input id="email" type="email"
               name="email"
               placeholder="Email"
               value="{{ old('email') }}"
               class="@error('email') is-invalid @enderror"
               required autocomplete="email" autofocus>
        @error('email')
          <div class="error-text">{{ $message }}</div>
        @enderror

        <input id="password" type="password"
               name="password"
               placeholder="Kata Sandi"
               class="@error('password') is-invalid @enderror"
               required autocomplete="current-password">
        @error('password')
          <div class="error-text">{{ $message }}</div>
        @enderror

        <div class="options">
          <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat akun saya
          </label>
          <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
        </div>

        <button type="submit">Login</button>
        
        <p class="register">Belum punya akun ? <a href="{{ route('register') }}">Daftar Akun</a></p>
      </form>
    </div>
    <footer>
      <p>Copyright © 2020 DIGITANI All Rights Reserved.</p>
    </footer>
  </div>
</body>
</html>
