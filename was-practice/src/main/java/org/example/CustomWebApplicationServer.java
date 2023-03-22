package org.example;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class CustomWebApplicationServer {
    private final int port;

    private final ExecutorService executorService = Executors.newFixedThreadPool(10);

    private static final Logger logger = LoggerFactory.getLogger(CustomWebApplicationServer.class);

    public CustomWebApplicationServer(int port) {
        this.port = port;
    }

    public void start() throws IOException {
        try (ServerSocket serverSocket = new ServerSocket(port)) {
            logger.info("[CustomWebApplicationServer] started {} port.", port);

            Socket clientSocket;
            logger.info("[CustomWebApplicationServer] waiting for client.");

            while ((clientSocket = serverSocket.accept()) != null) {
                logger.info("[CustomWebApplicationServer] client connected!");

                /**
                 * Step1 - 사용자 요청을 메인 Thread가 처리하도록 한다
                 * ==> 메인스레드에서 처리하기 때문에 다음 클라이언트의 요청은 앞선 요청이 끝날때까지 무한정 기다려야 한다.
                 * try (InputStream in = clientSocket.getInputStream(); OutputStream out = clientSocket.getOutputStream()) {
                 * BufferedReader br = new BufferedReader(new InputStreamReader(in, StandardCharsets.UTF_8));
                 * DataOutputStream dos = new DataOutputStream(out);
                 *
                 * HttpRequest httpRequest = new HttpRequest(br);
                 *
                 * GET /calculate?operand1=11&operator=*&operand2=55
                 * if (httpRequest.isGetRequest() && httpRequest.matchPath("/calculate")) { // GET 메소드 이면서 /calculate 요청인가?
                 * QueryStrings queryString = httpRequest.getQueryString();
                 *
                 * int operand1 = Integer.parseInt(queryString.getValue("operand1"));
                 * String operator = queryString.getValue("operator");
                 * int operand2 = Integer.parseInt(queryString.getValue("operand2"));
                 *
                 * int result = Calculator.calculate(new PositiveNumber(operand1), operator, new PositiveNumber(operand2));
                 * byte[] body = String.valueOf(result).getBytes(); // body 가져오기
                 *
                 * HttpResponse response = new HttpResponse(dos);
                 * response.response200Header("application/json", body.length);
                 * response.responseBody(body);
                 * }
                 *
                 * Step2 - 사용자 요청이 들어올 때마다 Thread를 새로 생성해서 사용자 요청을 처리하도록 한다.
                 * ==> 요청이 들어올때마다 쓰레드 생성시 성능이 매우 떨어짐
                 * new Thread(new ClientRequestHandler(clientSocket)).start();
                 *
                 * Step3 - Thread Pool을 적용해 안정적인 서비스가 가능하도록 한다.
                 */
                executorService.execute(new ClientRequestHandler(clientSocket));
            }
        }
    }
}
