<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous"
    />
    <style>

      .bubbles {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        overflow: hidden;
        margin: auto;
      }

      .bubble {
        position: absolute;
        left: var(--bubble-left-offset);
        bottom: -75%;
        display: block;
        width: var(--bubble-radius);
        height: var(--bubble-radius);
        border-radius: 50%;
        animation: float-up var(--bubble-float-duration) var(--bubble-float-delay) ease-in infinite;
      }

      .bubble::before {
        position: absolute;
        content: "";
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(136, 246, 251, 0.3);
        border-radius: inherit;
        animation: var(--bubble-sway-type) var(--bubble-sway-duration) var(--bubble-sway-delay) ease-in-out alternate infinite;
      }

      .bubble:nth-child(0) {
        --bubble-left-offset: 70vw;
        --bubble-radius: 2vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 3s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(1) {
        --bubble-left-offset: 92vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(2) {
        --bubble-left-offset: 60vw;
        --bubble-radius: 2vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(3) {
        --bubble-left-offset: 93vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(4) {
        --bubble-left-offset: 77vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(5) {
        --bubble-left-offset: 36vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(6) {
        --bubble-left-offset: 33vw;
        --bubble-radius: 6vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(7) {
        --bubble-left-offset: 84vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 7s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(8) {
        --bubble-left-offset: 3vw;
        --bubble-radius: 2vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 3s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(9) {
        --bubble-left-offset: 89vw;
        --bubble-radius: 1vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(10) {
        --bubble-left-offset: 87vw;
        --bubble-radius: 9vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 0s;
        --bubble-sway-delay: 3s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(11) {
        --bubble-left-offset: 98vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(12) {
        --bubble-left-offset: 80vw;
        --bubble-radius: 10vw;
        --bubble-float-duration: 7s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(13) {
        --bubble-left-offset: 97vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(14) {
        --bubble-left-offset: 28vw;
        --bubble-radius: 1vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(15) {
        --bubble-left-offset: 64vw;
        --bubble-radius: 10vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(16) {
        --bubble-left-offset: 3vw;
        --bubble-radius: 6vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(17) {
        --bubble-left-offset: 14vw;
        --bubble-radius: 6vw;
        --bubble-float-duration: 6s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(18) {
        --bubble-left-offset: 41vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 6s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(19) {
        --bubble-left-offset: 31vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(20) {
        --bubble-left-offset: 49vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 7s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 0s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(21) {
        --bubble-left-offset: 89vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(22) {
        --bubble-left-offset: 47vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(23) {
        --bubble-left-offset: 93vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(24) {
        --bubble-left-offset: 72vw;
        --bubble-radius: 5vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(25) {
        --bubble-left-offset: 68vw;
        --bubble-radius: 4vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(26) {
        --bubble-left-offset: 15vw;
        --bubble-radius: 5vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(27) {
        --bubble-left-offset: 20vw;
        --bubble-radius: 4vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(28) {
        --bubble-left-offset: 51vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(29) {
        --bubble-left-offset: 31vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(30) {
        --bubble-left-offset: 45vw;
        --bubble-radius: 5vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(31) {
        --bubble-left-offset: 9vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(32) {
        --bubble-left-offset: 62vw;
        --bubble-radius: 5vw;
        --bubble-float-duration: 6s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(33) {
        --bubble-left-offset: 8vw;
        --bubble-radius: 4vw;
        --bubble-float-duration: 6s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(34) {
        --bubble-left-offset: 76vw;
        --bubble-radius: 1vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(35) {
        --bubble-left-offset: 22vw;
        --bubble-radius: 10vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(36) {
        --bubble-left-offset: 82vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(37) {
        --bubble-left-offset: 30vw;
        --bubble-radius: 10vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(38) {
        --bubble-left-offset: 96vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(39) {
        --bubble-left-offset: 13vw;
        --bubble-radius: 5vw;
        --bubble-float-duration: 8s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(40) {
        --bubble-left-offset: 8vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(41) {
        --bubble-left-offset: 66vw;
        --bubble-radius: 1vw;
        --bubble-float-duration: 9s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(42) {
        --bubble-left-offset: 11vw;
        --bubble-radius: 4vw;
        --bubble-float-duration: 10s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(43) {
        --bubble-left-offset: 57vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(44) {
        --bubble-left-offset: 53vw;
        --bubble-radius: 5vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 2s;
        --bubble-sway-delay: 3s;
        --bubble-sway-type: sway-right-to-left;
      }

      .bubble:nth-child(45) {
        --bubble-left-offset: 18vw;
        --bubble-radius: 1vw;
        --bubble-float-duration: 7s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(46) {
        --bubble-left-offset: 72vw;
        --bubble-radius: 2vw;
        --bubble-float-duration: 7s;
        --bubble-sway-duration: 4s;
        --bubble-float-delay: 0s;
        --bubble-sway-delay: 1s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(47) {
        --bubble-left-offset: 80vw;
        --bubble-radius: 3vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 2s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(48) {
        --bubble-left-offset: 83vw;
        --bubble-radius: 8vw;
        --bubble-float-duration: 12s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 1s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(49) {
        --bubble-left-offset: 16vw;
        --bubble-radius: 10vw;
        --bubble-float-duration: 11s;
        --bubble-sway-duration: 5s;
        --bubble-float-delay: 4s;
        --bubble-sway-delay: 0s;
        --bubble-sway-type: sway-left-to-right;
      }

      .bubble:nth-child(50) {
        --bubble-left-offset: 0vw;
        --bubble-radius: 7vw;
        --bubble-float-duration: 7s;
        --bubble-sway-duration: 6s;
        --bubble-float-delay: 3s;
        --bubble-sway-delay: 4s;
        --bubble-sway-type: sway-right-to-left;
      }

      @keyframes float-up {
        to {
          transform: translateY(-175vh);
        }
      }

      @keyframes sway-left-to-right {
        from {
          transform: translateX(-100%);
        }
        to {
          transform: translateX(100%);
        }
      }

      @keyframes sway-right-to-left {
        from {
          transform: translateX(100%);
        }
        to {
          transform: translateX(-100%);
        }
      }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
              <a class="navbar-brand" href="#">Navbar</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Dropdown
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Another action</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                  </li>
                </ul>
                <form class="d-flex" role="search">
                  <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
              </div>
            </div>
          </nav>
    </header>
    <main class="my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <img src="https://picsum.photos/id/237/600/400" alt="if you know, you know" class="img-fluid">
                </div>
                <div class="col-md-5">
                    <h1 class="mt-5">TagLine</h1>
                    <p class="mt-4">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                        Odio impedit suscipit quia quo, consectetur reiciendis illo tenetur cupiditate? 
                        Quod perspiciatis omnis a ea recusandae est earum architecto dolores quaerat ad.
                    </p>
                    <button type="button" class="btn btn-primary mt-3">Call to Action!</button>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <div class="bg-secondary text-white my-5 py-4 card text-center">
                        <div class="card-body">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Quas, eveniet voluptatibus. 
                            Aliquam voluptate ipsum nobis rem qui dolorem blanditiis optio, 
                            dolores voluptatum nulla nostrum architecto repudiandae velit voluptates tenetur reprehenderit.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card-group">
                        <div class="card">
                          <img src="https://picsum.photos/300/200" class="card-img-top" alt="...">
                          <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <div class="">
                                <a href="#"><button type="button" class="btn btn-primary">Read more</button></a>
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <img src="https://picsum.photos/301/200" class="card-img-top" alt="...">
                          <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                            <div class="">
                                <a href="#"><button type="button" class="btn btn-primary">Read more</button></a>
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <img src="https://picsum.photos/302/200" class="card-img-top" alt="...">
                          <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                            <div class="">
                                <a href="#"><button type="button" class="btn btn-primary">Read more</button></a>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="text-center">
                <h2>Get in Touch</h2>
                <p class="lead">Questions? Do not hesitate to contact us.</p>
            </div><div class="row justify-content-center my-5">
                <div class="col-lg-6">
                    <form action="">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" placeholder="my@email.com" class="form-control">
        
                        <div class="form-floating my-5">
                            <input type="name" id="name" placeholder="e.g. James" class="form-control">
                            <label for="name" class="form-label">Name:</label>
                        </div>
        
                        <label for="subject" class="form-label">Subject:</label>
                        <select name="subject" id="subject" class="form-select">
                            <option value="pricing">Pricing</option>
                            <option value="technical" selected>Technical Question</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="form-floating my-5">
                            <textarea 
                                name="query" 
                                id="query" 
                                style="height: 150px;"
                                class="form-control" 
                                placeholder="Write a message"
                            ></textarea>
                            <label for="query">Write a message</label>
                        </div>
        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Send!</button>
                        </div>
                    </form>
                </div>
          </div>
          <div class="bubbles">
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
          </div>
    </main>
    <footer class="footer mt-auto py-3 bg-dark">
        <div class="container">
          <span class="text-white">Copyrights <a href="https://mdbootstrap.com">mDBootstrap.com</a></span>
        </div>
      </footer>
    <script 
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" 
        crossorigin="anonymous"
    ></script>
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" 
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" 
        crossorigin="anonymous"
    ></script>
</body>
</html>