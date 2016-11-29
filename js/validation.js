$(document).ready(function() {
  $("#ItemForm").validate(
    {
      rules:
      {
        txtPlaceBid:
        {
          required: true
        }
      },
      messages:
      {
        txtPlaceBid:
        {
          Required: "Please enter a bid"
        }
      }
    });
});

