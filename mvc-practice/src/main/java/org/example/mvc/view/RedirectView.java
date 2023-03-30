package org.example.mvc.view;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.Map;

public class RedirectView implements View {
    // 리다이렉트 이후의 값을 리턴해달라는 의미
    public static final String DEFAULT_REDIRECT_PREFIX = "redirect:";

    private final String name;

    public RedirectView(String name) {
        this.name = name;
    }
    // 리다이렉트 방식

    @Override
    public void render(Map<String, ?> model, HttpServletRequest request, HttpServletResponse response) throws Exception {
        model.forEach(request::setAttribute);
        // sendredirect해주기 때문에 자체적으로 redirect가 붙는다. => 그래서 잘라주는 것
        response.sendRedirect(name.substring(DEFAULT_REDIRECT_PREFIX.length()));
    }
}