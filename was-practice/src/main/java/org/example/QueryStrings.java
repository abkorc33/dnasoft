package org.example;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class QueryStrings { // 일급컬렉션
    private List<QueryString> queryStrings = new ArrayList<>();

    // "operand1=11  operator=*   operand2=55"
    public QueryStrings(String queryStringLine) {
        String[] queryStringTokens = queryStringLine.split("&");
        Arrays.stream(queryStringTokens)
                .forEach(queryString -> {
                    String[] values = queryString.split("=");
                    if (values.length != 2) {
                        throw new IllegalArgumentException("잘못된 QueryString 포맷을 가진 문자열입니다.");
                    }
                    queryStrings.add(new QueryString(values[0], values[1]));
                });
    }
    // 여러 개의 QueryString이 있는 경우 각각의 key에 대해 존재하는지 filter를 통해 먼저 거르고
    // 존재한다면 Value값을 가져와 첫번째 것을 반환하도록 하는 코드가 추가
    public String getValue(String key) {
        return this.queryStrings.stream()
                .filter(queryString -> queryString.exists(key))
                .map(QueryString::getValue)
                .findFirst()
                .orElse(null);
    }
}
