package org.example.mvc.view;

import static org.example.mvc.view.RedirectView.DEFAULT_REDIRECT_PREFIX;

public class JspViewResolver implements ViewResolver {
    @Override
    public View resolveViewName(String viewName) {
        // 리다이렉트 형태면 리다이렉트 뷰를 보여준다.
        if (viewName.startsWith(DEFAULT_REDIRECT_PREFIX)) {
            return new RedirectView(viewName);
        }
        // 아니면 jsp뷰 보여준다. => .jsp를 뷰리졸버에서 붙여주기 때문에 리턴할 때 jsp안붙여도 된다.
        return new JspView(viewName + ".jsp");
    }
}