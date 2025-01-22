document.getElementById('lm').addEventListener('click', function() {
    window.location.href = 'about-us.php';
});

document.getElementById('vm').addEventListener('click', function() {
    window.location.href = 'menu.php';
});

let elements = document.getElementsByClassName('inside');
for (let i = 0; i < elements.length; i++) {
    elements[i].addEventListener('click', function() {
        window.location.href = 'menu.php';
    });
}



// let profile = document.querySelector('#head .profile-detail');

// document.querySelector('#user-btn').onclick = () =>{
//   profile.classList.toggle('active');
//   // searchForm.classList.remove('active');
// }

const userBtn = document.getElementById('user-btn');
  const profileDetail = document.querySelector('.profile-detail');

// Add click event listener to the user button
userBtn.addEventListener('click', function() {
    // Toggle the display property of the profile detail div
    if (profileDetail.style.display === 'block') {
        profileDetail.style.display = 'none'; // Hide the div
    } else {
        profileDetail.style.display = 'block'; // Show the div
    }
});

  