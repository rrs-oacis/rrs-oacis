import javax.swing.*;
import java.io.*;
import java.net.*;
import java.nio.ByteBuffer;
import java.nio.channels.*;
import java.util.*;

public class RrsoacisNodeCollector
{
    private static final int PORT = 49138;
    private static final int SENDER_CONTROL_PORT = 49238;
    private static final int RECEIVER_CONTROL_PORT = 49338;

    public static void main(String[] argv) throws Exception
    {
        String command = (argv.length >= 1 ? argv[0] : "s");
        System.setProperty("java.net.preferIPv4Stack", "true");

        switch (command)
        {
            case "r":
                closeReceiverProc("127.0.0.1");
                receiverProc ((argv.length == 2 ? Integer.valueOf(argv[1]) : 10));
                break;
            case "c":
                closeReceiverProc((argv.length == 2 ? argv[1] : "127.0.0.1"));
                break;
            case "e":
                extendReceiverProc((argv.length == 2 ? argv[1] : "127.0.0.1"));
                break;
            case "s":
            default:
                senderProc();
        }
    }

    public static void senderProc()
    {
        try
        {
            Runtime runtime = Runtime.getRuntime();
            runtime.exec(new String[]{"xhost", "+"});
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }

        String broadcastAddress = getBroadcastAddress();
        new Thread(new Runnable()
        {
            @Override
            public void run()
            {
                JOptionPane.showConfirmDialog(null,
                        "RRS-OACIS NodeCollector beacon is running...\nClick \"OK\" to quit.",
                        "RRS-OACIS NodeCollector", JOptionPane.DEFAULT_OPTION, JOptionPane.PLAIN_MESSAGE);
                System.out.println("");
                System.exit(0);
            }
        }).start();

        new Thread(new Runnable()
        {
            @Override
            public void run()
            {
                try
                {
                    ServerSocketChannel conServer = ServerSocketChannel.open();
                    conServer.bind(new InetSocketAddress(SENDER_CONTROL_PORT));
                    while (true)
                    {
                        SocketChannel channel = conServer.accept();
                        ByteBuffer recvBuf = ByteBuffer.allocate(3);
                        channel.read(recvBuf);
                        if ((new String(recvBuf.array())).equals("ROc"))
                        {
                            //runFlag.set(false);
                            System.out.println("");
                            System.exit(0);
                        }
                    }
                }
                catch (IOException e)
                {
                    e.printStackTrace();
                }
            }
        }).start();

        try
        {
            DatagramChannel sendCh = DatagramChannel.open();
            sendCh.socket().setBroadcast(true);
            ByteBuffer sendBuf = ByteBuffer.wrap(("ROs#" + System.getProperty("user.name")).getBytes("UTF-8"));
            InetSocketAddress portISA = new InetSocketAddress(broadcastAddress, PORT);
            while (true)
            {
                //while (runFlag.get()) {
                sendCh.send(sendBuf, portISA);
                System.out.print(".");
                sendBuf.clear();

                try
                {
                    Thread.sleep(3000);
                }
                catch (InterruptedException e)
                {
                }
            }
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }
        //sendCh.close();
        //System.out.println("sendCh close");
    }

    public static void receiverProc(int timeout)
    {
        new Thread(new Runnable()
        {
            @Override
            public void run()
            {
                timeoutThread.start();
                try
                {
                    ServerSocketChannel conServer = ServerSocketChannel.open();
                    conServer.bind(new InetSocketAddress(RECEIVER_CONTROL_PORT));
                    while (true)
                    {
                        SocketChannel channel = conServer.accept();
                        ByteBuffer recvBuf = ByteBuffer.allocate(3);
                        channel.read(recvBuf);
                        if ((new String(recvBuf.array())).equals("ROc"))
                        {
                            System.exit(0);
                        }
                        else if ((new String(recvBuf.array())).equals("ROe"))
                        {
                            timeoutThread.interrupt();
                        }
                    }
                }
                catch (IOException e)
                {
                    e.printStackTrace();
                }
            }

            Thread timeoutThread = new Thread(new Runnable()
            {
                @Override
                public void run()
                {
                    while (true)
                    {
                        try { Thread.sleep(3000); }
                        catch (InterruptedException e) { continue; }
                        System.exit(0);
                    }
                }
            });
        }).start();

        try
        {
            DatagramChannel recvCh = DatagramChannel.open();
            recvCh.socket().bind(new InetSocketAddress(PORT));

            ByteBuffer recvBuf = ByteBuffer.allocate(1024);
            while (true)
            {
                InetSocketAddress remoteAddr = (InetSocketAddress) recvCh.receive(recvBuf);
                recvBuf.flip();
                int limit = recvBuf.limit();
                String recvStr = new String(recvBuf.array(), recvBuf.position(), limit, "UTF-8");
                if (recvStr.startsWith("ROs#"))
                {
                    String username = recvStr.replaceFirst("^ROs#", "");
                    System.out.println(username + "@" + remoteAddr.getAddress().getHostAddress());
                    recvBuf.clear();

                    // control sender
                    SocketChannel channel = SocketChannel.open();
                    channel.connect(new InetSocketAddress(remoteAddr.getAddress().getHostAddress(), SENDER_CONTROL_PORT));
                    channel.write(ByteBuffer.wrap("ROc".getBytes()));
                }
            }

            //recvCh.close();
            //System.out.println("recvCh close");
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }
    }

    public static void closeReceiverProc(String remoteAddr)
    {
        try
        {
            SocketChannel channel = SocketChannel.open();
            channel.connect(new InetSocketAddress(remoteAddr, RECEIVER_CONTROL_PORT));
            channel.write(ByteBuffer.wrap("ROc".getBytes()));
        }
        catch (IOException e)
        {
        }
    }

    public static void extendReceiverProc(String remoteAddr)
    {
        try
        {
            SocketChannel channel = SocketChannel.open();
            channel.connect(new InetSocketAddress(remoteAddr, RECEIVER_CONTROL_PORT));
            channel.write(ByteBuffer.wrap("ROe".getBytes()));
        }
        catch (IOException e)
        {
        }
    }

    private static final String getBroadcastAddress()
    {
        try
        {
            for (Enumeration<NetworkInterface> niEnum = NetworkInterface.getNetworkInterfaces(); niEnum.hasMoreElements(); )
            {
                NetworkInterface ni = niEnum.nextElement();
                if (!ni.isLoopback())
                {
                    for (InterfaceAddress interfaceAddress : ni.getInterfaceAddresses())
                    {
                        if (interfaceAddress != null)
                        {
                            InetAddress broadcastAddress = interfaceAddress.getBroadcast();
                            if (broadcastAddress != null)
                            {
                                return broadcastAddress.toString().substring(1);
                            }
                        }
                    }
                }
            }
        }
        catch (SocketException e)
        {
        }

        return null;
    }
}

