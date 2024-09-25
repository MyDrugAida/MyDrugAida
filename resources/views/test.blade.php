<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Firebase RealTime Chat</title>
    <link rel="stylesheet" href="./index.css">
    <style>
        #messages {
          height: 400px; /* Adjust the height as needed */
          overflow-y: auto; /* This makes the messages scrollable */
          padding: 10px;
          border: 1px solid #ddd;
          list-style-type: none;
          margin: 0;
        }
        
        #messages li {
          margin-bottom: 10px;
        }
        
        .reply {
          background-color: #f1f1f1;
          padding: 5px;
          border-left: 4px solid #ccc;
          margin-bottom: 5px;
        }
        
        button {
          margin-left: 10px;
        }
    </style>
  </head>
  <body>
    <header>
      <h2>Firebase RealTime Chat</h2>
    </header>

    <div id="chat">
      <!-- messages will display here -->
      <ul id="messages"></ul>

      <!-- form to send message -->
      <form id="message-form">
        <input id="message-input" type="text" placeholder="Type a message..." />
        <button id="message-btn" type="submit">Send</button>
        <input type="hidden" id="replyToInput" value="" />
      </form>
    </div>

    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-auth.js"></script>
    <script>
      // Firebase configuration
     const firebaseConfig = {
      apiKey: "AIzaSyCbkzq5FIxRiqB0sm2xKwGUhhVDERM31ek",
      authDomain: "mydrugaida-1234.firebaseapp.com",
      databaseURL: "https://mydrugaida-1234-default-rtdb.firebaseio.com",
      projectId: "mydrugaida-1234",
      storageBucket: "mydrugaida-1234.appspot.com",
      messagingSenderId: "259002453482",
      appId: "1:259002453482:web:ec02ba242118cd34c9b693",
      measurementId: "G-3L3Q4S7X4P"
    };
    
      // Assume you received the idToken from your backend
      const idToken = "<idToken_from_backend>";
      

      firebase.initializeApp(firebaseConfig);
      /*
      // Authenticate the user with the token
      firebase.auth().signInWithCustomToken(idToken).then((userCredential) => {
        alert("User authenticated with custom token:");
        // Now the user is authenticated and you can allow them to use the chat feature
      })
      .catch((error) => {
        alert("Error authenticating user:");
      }); */
        
      const database = firebase.database();

      // Prompt for username and chat room
      const username = prompt("Please Tell Us Your Name");
      const chatRoom = prompt("Enter Chat Room ID");

      const formM = document.getElementById("message-form");
      formM.addEventListener("submit", sendMessage);

      // Variables for pagination and limit
      let messagesFetched = 0; // Keeps track of the number of messages fetched
      const limit = 10; // How many messages to load at a time

      // Send message to the specific chat room, with reply functionality
      function sendMessage(e) {
        e.preventDefault();
        const timestamp = Date.now();
        const messageInput = document.getElementById("message-input");
        const message = messageInput.value;

        if (message.trim() === "") {
          alert("Please enter a message");
          return;
        }

        const replyTo = document.getElementById("replyToInput").value || null;

        // Clear input box after sending
        messageInput.value = "";
        document.getElementById("replyToInput").value = ""; // Clear reply reference

        // Send the message along with the username, timestamp, and reply reference
        database.ref(`chats/${chatRoom}/messages/` + timestamp).set({
          username,
          message,
          timestamp, // Save the timestamp for later checks (for edit time window)
          replyTo // Save the replyTo messageID if available
        });
      }
      
      database.ref(`chats/${chatRoom}/messages`).on("child_added", function(snapshot) {
        if(document.getElementById('messages').innerHTML == ''){
          console.log('Do nothing');
        }else{
      // Every time a new message is added, this listener will be triggered
        displayMessage(snapshot); // Call the displayMessage function to show the new message
        }
       });

      // Function to load the most recent messages initially (with limit)
      function loadRecentMessages() {
        const fetchRecentChat = database
          .ref(`chats/${chatRoom}/messages`)
          .orderByKey()
          .limitToLast(limit); // Fetch the most recent 'limit' messages

        fetchRecentChat.once("value", function (snapshot) {
          const messages = snapshot.val();
          for (const messageID in messages) {
            displayMessage({ key: messageID, val: () => messages[messageID] });
          }
          // Set the total fetched messages count
          messagesFetched = Object.keys(messages).length;
          // Scroll to bottom of chat after initial load
          const messagesList = document.getElementById("messages");
          messagesList.scrollTop = messagesList.scrollHeight;
        });
      }

      // Function to fetch older messages (when scrolling up)
      function loadOlderMessages() {
        const oldestMessageID = document.getElementById("messages").firstChild.id;
        if (!oldestMessageID) return;

        const fetchOldChat = database
          .ref(`chats/${chatRoom}/messages`)
          .orderByKey()
          .endBefore(oldestMessageID) // Fetch messages before the oldest one currently in the list
          .limitToLast(limit); // Fetch 'limit' older messages

        fetchOldChat.once("value", function (snapshot) {
          const messages = snapshot.val();
          if (messages) {
            for (const messageID in messages) {
              displayMessage({ key: messageID, val: () => messages[messageID] }, true); // Pass true to indicate prepending
            }
            // Update the fetched message count
            messagesFetched += Object.keys(messages).length;
          }
        });
      }

      // Fetch the initial set of messages (most recent)
      loadRecentMessages();

      // Add a scroll event listener to load older messages when scrolling up
      const messagesList = document.getElementById("messages");
      messagesList.addEventListener("scroll", function () {
        if (messagesList.scrollTop === 0) {
          //If the user has scrolled to the top, load older messages
          loadOlderMessages();
        }
      });

      // Function to display messages, optionally prepend for older messages
      function displayMessage(snapshot, prepend = false) {
        const messages = snapshot.val();
        const messageID = snapshot.key;

        let replyHTML = ""; // To hold reply display if applicable
        if (messages.replyTo) {
          // If this message is a reply, fetch and display the replied message
          replyHTML = `<div class="reply" onclick="scrollToMessage('${messages.replyTo}')">
            Replied to: <span id="replied-message-${messages.replyTo}">Loading...</span>
          </div>`;
          fetchRepliedMessage(messages.replyTo); // Fetch the replied message text
        }

        /*const messageHTML = `<li id="${messageID}">
          ${replyHTML}
          <span>${messages.username}: </span>${messages.message}
          <button onclick="setReplyTo('${messageID}')">Reply</button>
        </li>`;*/
        
        const messageHTML = `<li id="${messageID}">
          ${replyHTML}
          <span>${messages.username}: </span>${messages.message}
          <button onclick="setReplyTo('${messageID}')">Reply</button>
          <button onclick="editMessage('${messageID}')">Edit</button>
          <button onclick="deleteMessage('${messageID}')">Delete</button>
        </li>`;

        // Check if we need to prepend older messages
        if (prepend) {
          document.getElementById("messages").insertAdjacentHTML('afterbegin', messageHTML);
        } else {
          document.getElementById("messages").insertAdjacentHTML('beforeend', messageHTML);
        }
      }

      // Set replyTo message when "Reply" button is clicked
      function setReplyTo(messageID) {
        document.getElementById("replyToInput").value = messageID; // Store the messageID being replied to
      }

      // Function to fetch the original message being replied to
      /*function fetchRepliedMessage(replyToID) {
        database.ref(`chats/${chatRoom}/messages/${replyToID}`).once("value", (snapshot) => {
          const repliedMessage = snapshot.val();
          if (repliedMessage) {
            document.getElementById(`replied-message-${replyToID}`).innerText = repliedMessage.message;
          }
        });
      }*/
      
      function fetchRepliedMessage(messageID) {
          database.ref(`chats/${chatRoom}/messages/${messageID}`).once("value")
            .then(snapshot => {
              const repliedMessage = snapshot.val();
              if (repliedMessage) {
                document.getElementById(`replied-message-${messageID}`).innerText = `${repliedMessage.username}: ${repliedMessage.message}`;
              }else{
                
              }
            });
        }
        
        function editMessage(messageID) {
          const newMessage = prompt("Edit your message:");
          if (newMessage) {
            database.ref(`chats/${chatRoom}/messages/${messageID}`).update({
              message: newMessage
            });
          }
        }
        
                
        // Delete message function
        function deleteMessage(messageID) {
          if (confirm("Are you sure you want to delete this message?")) {
            database.ref(`chats/${chatRoom}/messages/${messageID}`).remove();
          }
        }
        
        // Listen for message changes and removals
        database.ref(`chats/${chatRoom}/messages`).on("child_changed", function(snapshot) {
          const updatedMessage = snapshot.val();
          const messageID = snapshot.key;
          document.getElementById(messageID).innerHTML = `<span>${updatedMessage.username}: </span>${updatedMessage.message}
            <button onclick="setReplyTo('${messageID}')">Reply</button>
            <button onclick="editMessage('${messageID}')">Edit</button>
            <button onclick="deleteMessage('${messageID}')">Delete</button>`;
        });
        
        database.ref(`chats/${chatRoom}/messages`).on("child_removed", function(snapshot) {
          const messageID = snapshot.key;
          const messageElement = document.getElementById(messageID);
          if (messageElement) {
            messageElement.remove(); // Remove the message from the UI
          }
        });

      // Function to scroll to a specific message when clicked on the reply
      function scrollToMessage(messageID) {
        const messageElement = document.getElementById(messageID);
        if (messageElement) {
          // Scroll to the message if it's already loaded
          messageElement.scrollIntoView({ behavior: "smooth", block: "center" });
        } else {
          // Load the message from Firebase if it's not yet loaded
          database.ref(`chats/${chatRoom}/messages/${messageID}`).once("value", (snapshot) => {
            if (snapshot.exists()) {
              displayMessage({ key: messageID, val: () => snapshot.val() });
              document.getElementById(messageID).scrollIntoView({ behavior: "smooth", block: "center" });
            } else {
              alert("Message not found.");
            }
          });
        }
      }
    </script>
  </body>
</html>