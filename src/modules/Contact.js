class Contact {

  constructor() {
    this.contactFormSubmit = document.getElementById('contact-form-submit');
    this.contactFormSubmit.addEventListener('click', e => validateForm(e));
  }



  validateForm = (event) => {
    console.log('validation received: ', event);
    event.preventDefault();
    event.stopPropagation();
  
    //Full name
    const fullName = document.getElementById('full-name') !== null ?
        document.getElementById('full-name').value :
        '';
  
    //Email
    const email = document.getElementById('email') !== null ?
        document.getElementById('email').value :
        '';
  
    //Message
    const message = document.getElementById('message') !== null ?
        document.getElementById('message').value :
        '';
  
    const validationMessages = [];
    if (fullName.length === 0) {
      validationMessages.push('Please enter a valid name.');
    }
  
    if (email.length === 0 || !emailIsValid(email).bind(this)) {
      validationMessages.push('Please enter a valid email address.');
    }
  
    if (message.length === 0) {
      validationMessages.push('Please enter a valid message.');
    }
  
    if (validationMessages.length === 0) {
  
      //Submit the form
      document.getElementById('contact-form').submit();
  
    } else {
  
      //Delete all the existing validation messages from the DOM
      const parent = document.getElementById('validation-messages-container');
      while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
      }
  
      //Add the new validation messages to the DOM
      validationMessages.forEach(function(validationMessage, index) {
  
        //add message to the DOM
        const divElement = document.createElement('div');
        divElement.classList.add('validation-message');
        const node = document.createTextNode(validationMessage);
        divElement.appendChild(node);
  
        const element = document.getElementById('validation-messages-container');
        element.appendChild(divElement);
  
      });
  
    }
  
  }


  emailIsValid = (email) => {

    const regex = /\S+@\S+\.\S+/;
    return regex.test(email);
  
  }
}

export default Contact;