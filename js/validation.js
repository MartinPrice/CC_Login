$(document).ready(function() {
    $("#ItemForm").validate( {
    rules:
    {
      txtPlaceBid:
      {
        required: true,
        range:[0.10,200.00]
      }
    },
    messages:
    {
      txtPlaceBid:
      {
        required: "Please enter a bid",
        range: "Please enter a value between 0.10 and 200.00"
      }
    }
  });
});