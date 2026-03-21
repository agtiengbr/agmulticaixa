$(window).on("load", function() {
  if(timeout > 0) {

    interval = setTimeout(refreshToken, timeout);
    
    function refreshToken(){ 
      expired_token_notification = `<div class="alert alert-danger" role="alert">
      O token expirou, a página será recarregada.
      </div>`;
      
      $("#multicaixa_gpo").before(expired_token_notification);
      
      setTimeout(function() { window.location.reload();}, 2000);
    }
    
    $("#multicaixa_gpo").addClass('iframe_custom');
    
    $("section.container").addClass('container_custom');
    
    $(".row.payment-submit > div").addClass('payment_submit_custom');
    
    $("#content-hook_order_confirmation .card-block").addClass('card-block-custom');

    modal_warning_payment = `<div class="modal fade alert-payment" id="modalAlertPayment" tabindex="-1" aria-labelledby="modalAlertPaymentLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="message_warning">AVISO</h5>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="alert alert-warning" role="alert">
                                        Para realizar o pagamento do seu pedido você deve inserir o número de telefone cadastrado no Multicaixa Express.
                                      </div>
                                      <div class="alert alert-info" role="alert">
                                        <b>O reconhecimento do pagamento em nossa loja pode levar até 15 minutos após a realização do mesmo.</b>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>`;

    $("body#order-confirmation").append(modal_warning_payment);
    $('#modalAlertPayment').appendTo("body").modal('show');
  }
});

window.addEventListener('message', receiveMessage, false);

function receiveMessage(event) {
  
  if (event.origin !== base_url) { return; }

    if(!event.data) {
      return;
    }

    $.ajax({
      url: url_mcx_order,
      data: `gpo_id=${event.data}&order_id=${order_id}`,
      type: 'POST',
      dataType: 'JSON',
    })
    .then(function(data){
    })
    .fail(function(data){
      erro_api = `<div class="alert alert-danger" role="alert">
                    Ocorreu um erro ao salvar o pedido.
                  </div>`;

      $("#multicaixa_gpo").before(erro_api);
    });

    return false;
}
