<?php 
  include "topmenu.php";
?>
<div class="container">
  <h1 class="title">Registration</h1>
  <form action="" method="post">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input
        type="text"
        class="form-control"
        name="name"
        id="name"
        placeholder="Enter your Name"
      />
      <div class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input
        type="text"
        class="form-control"
        name="email"
        id="email"
        placeholder="Enter your Email"
      />
      <div class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label for="pwd" class="form-label">Password</label>
      <input
        type="password"
        class="form-control"
        name="pwd"
        id="pwd"
        placeholder="Enter your password"
      />
      <div class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label for="conf_pwd" class="form-label">Confirm Password</label>
      <input
        type="password"
        class="form-control"
        name="conf_pwd"
        id="conf_pwd"
        placeholder="Confirm password"
      />
      <div class="text-danger"></div>
    </div>

    <div class="form-check">
      <input
        class="form-check-input"
        name=""
        id=""
        type="checkbox"
        value="checkedValue"
        aria-label="Text for screen reader"
      />
      <div class="text-danger"></div>
    </div>

    <div class="text-center mb-4">
      <button
        type="submit"
        class="btn btn-primary"
      >
        Register
      </button>
    </div>
    <p>Already Registerd? Login <a href="login.php">here</a></p>
  </form>
</div>
</body>
</html>