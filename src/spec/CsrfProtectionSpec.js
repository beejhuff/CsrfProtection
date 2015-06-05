describe("Cross-Site Request Forgery Protection", function() {

    it("Inject form key into all forms on the page", function() {

        var formFirst = $('<form method="post">');
        var formSecond = $('<form method="post">');
        $(document.body).append(formFirst);
        $(document.body).append(formSecond);

        expect(wrapCsrfToken("testing")).toBe(true);

        var inputFirst = $('input', formFirst);
        expect(inputFirst.length).toBe(1);
        expect(inputFirst.val()).toBe('testing');

        var inputSecond = $('input', formSecond);
        expect(inputSecond.length).toBe(1);
        expect(inputSecond.val()).toBe('testing');

        formFirst.remove();
        formFirst = null;
        formSecond.remove();
        formSecond = null;

    });

    it("Inject form key into all ajax calls", function() {

        jasmine.Ajax.install();

        expect(wrapCsrfToken("testing")).toBe(true);

        $.ajax({
            type: "POST",
            url: "/",
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        });

        expect(jasmine.Ajax.requests.mostRecent().requestHeaders['X-CSRF-Token']).toBeDefined();
        expect(jasmine.Ajax.requests.mostRecent().requestHeaders['X-CSRF-Token']).toBe('testing');

        jasmine.Ajax.uninstall();

    });

});
