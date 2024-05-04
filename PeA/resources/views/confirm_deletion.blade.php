<!-- resources/views/confirm_deletion.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Apagar Conta</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous"
    />
</head>
<body>
    @include('navbar')
    <main class="my-5">
        <div class="container">
          <div class="container mt-auto">
            <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header text-left">Registo concluído!</div>
                      <div class="card-body text-center">
                          <h2>Confirm Delete</h2>
                            <p>Please confirm deletion of user {{ $user->name }} by entering your password:</p>
                            <form method="post" action="{{ route('user.destroy', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <input type="password" name="password" placeholder="Your Password" required>
                                <button type="submit">Confirm Delete</button>
                            </form>
                          
                      </div>
                  </div>
                  
              </div>
            </div>
        </div>
        </div>
      </main>
    
    @include('footer')
</body>
</html>
