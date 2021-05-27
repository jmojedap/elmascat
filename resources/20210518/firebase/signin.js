// Variables
//-----------------------------------------------------------------------------
const auth = firebase.auth();

// Functions
//-----------------------------------------------------------------------------
function googleSignIn(){
    console.log('Vamos con Google');
    const provider = new firebase.auth.GoogleAuthProvider();
    auth.signInWithPopup(provider)
    .then(result => {
        console.log(result);
        console.log('Entramos con Google');  
    })
    .catch(err => {
        console.log(err);
    });
}

// Returns the signed-in user's profile Pic URL.
function getProfilePicUrl() {
    return firebase.auth().currentUser.photoURL || url_app + 'resources/images/users/sm_user.png';
  }

// Initiate firebase auth.
function initFirebaseAuth() {
    // Listen to auth state changes.
    firebase.auth().onAuthStateChanged(authStateObserver);
}


function authStateObserver(user){
    console.log(user);
    if (user) { // User is signed in!
        if ( window.location.href == url_app + 'firebase/signin' )
        {
            window.location = url_app + 'firebase/messages'
        }
        // Get the signed-in user's profile pic and name.
        var profilePicUrl = getProfilePicUrl();
        console.log(profilePicUrl);
        //var userName = getUserName();
    
        // Set the user's profile pic and name.
        //userPicElement.style.backgroundImage = 'url(' + profilePicUrl + ')';
        userPicElement.setAttribute.src = profilePicUrl;
        //userNameElement.textContent = userName;
    
        // Show user's profile and sign-out button.
        //userNameElement.removeAttribute('hidden');
        //userPicElement.removeAttribute('hidden');
        //signOutButtonElement.removeAttribute('hidden');
    
        // Hide sign-in button.
        //signInButtonElement.setAttribute('hidden', 'true');
    
        // We save the Firebase Messaging Device token and enable notifications.
        //saveMessagingDeviceToken();
      } else { // User is signed out!
        if ( window.location.href != url_app + 'firebase/signin' )
        {
            window.location = url_app + 'firebase/signin'
        }
        // Hide user's profile and sign-out button.
        /*userNameElement.setAttribute('hidden', 'true');
        userPicElement.setAttribute('hidden', 'true');
        signOutButtonElement.setAttribute('hidden', 'true');
    
        // Show sign-in button.
        signInButtonElement.removeAttribute('hidden');*/
      }

}
  
// initialize Firebase
initFirebaseAuth();

// Shortcuts to DOM Elements.
var googleSignInButtonElement = document.getElementById('google-sign-in');

console.log('hola variables');
console.log(googleSignInButtonElement);

//Listeners
googleSignInButtonElement.addEventListener('click', googleSignIn);