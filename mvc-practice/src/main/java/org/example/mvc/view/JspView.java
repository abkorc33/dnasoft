package org.example.mvc.view;

import javax.servlet.RequestDispatcher;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.Map;

public class JspView implements View {
    private final String name;

    public JspView(String name) {
        this.name = name;
    } // 생성자로 받는다.

    @Override
    public void render(Map<String, ?> model, HttpServletRequest request, HttpServletResponse response) throws Exception {
        // jsp뷰로 포워드 해주는 코드 / 포워드방식
        model.forEach(request::setAttribute); // 모델에 값이 있으면 request setattribute로 모두 세팅해주세요
        RequestDispatcher rd = request.getRequestDispatcher(name);
        rd.forward(request, response);
    }
}