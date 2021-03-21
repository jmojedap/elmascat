// Variables
//-----------------------------------------------------------------------------
const auth = firebase.auth();

// Functions
//-----------------------------------------------------------------------------
function signOut()
{
    console.log('SALIENDO');
    auth.signOut().then(() => {
        console.log('Estamos en appSignOut');
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
    console.log('ESTAMOS EN AUSTHSTATEOBSERVER');
    console.log(auth.currentUser);
    if (user) { // User is signed in!
        
        // Get the signed-in user's profile pic and name.
        var profilePicUrl = getProfilePicUrl();
        console.log(profilePicUrl);
        //var userName = getUserName();
    
        // Set the user's profile pic and name.
        //userPicElement.style.backgroundImage = 'url(' + profilePicUrl + ')';
        //document.getElementById('user-pic').setAttribute;
        userPicElement.setAttribute('src', profilePicUrl);
        navBarDisplayName.innerHTML = user.displayName;
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
        window.location = url_app + 'firebase/signin'
        
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
//-----------------------------------------------------------------------------
var userPicElement = document.getElementById('user-pic');
var navBarDisplayName = document.getElementById('navbar_display_name');
var signOutButtonElement = document.getElementById('sign-out');

// LISTENERS
//-----------------------------------------------------------------------------
signOutButtonElement.addEventListener('click', signOut);