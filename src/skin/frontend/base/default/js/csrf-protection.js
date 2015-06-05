function wrapCsrfToken(token)
{
    var forms = document.getElementsByTagName('form');
    for (var i = 0; i < forms.length; i++)
    {
        var form = forms[i];
        var method = form.getAttribute('method');
        if (method && method.toLowerCase() == 'post')
        {
            var tokenElement = document.createElement('input');
            tokenElement.setAttribute('type', 'hidden');
            tokenElement.setAttribute('name', 'form_key');
            tokenElement.setAttribute('value', token);
            form.appendChild(tokenElement);
        }
    }
    jQuery("body").bind("ajaxSend", function(elm, xhr, s){
        if (s.type == "POST") {
            xhr.setRequestHeader('X-CSRF-Token', token);
        }
    });
    (function() {
        var send = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function(data) {
            this.setRequestHeader('X-CSRF-Token', token);
            return send.apply(this, arguments);
        };
    }());
}
