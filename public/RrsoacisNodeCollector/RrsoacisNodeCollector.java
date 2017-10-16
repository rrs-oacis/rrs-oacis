import javax.swing.*;
import java.io.*;
import java.net.*;
import java.nio.ByteBuffer;
import java.nio.channels.*;
import java.util.*;

public class RrsoacisNodeCollector
{
    private static int timeout = 10000;
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
                timeout = ((argv.length == 2 ? Integer.valueOf(argv[1]) : 10));
                receiverProc ();
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
                            channel.close();
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
            while (true)
            {
                //while (runFlag.get()) {
                for (InterfaceAddress address : getBroadcastAddress())
                {
                    DatagramChannel sendCh = DatagramChannel.open();
                    sendCh.socket().setBroadcast(true);
                    ByteBuffer sendBuf = ByteBuffer.wrap(("ROs#" + System.getProperty("user.name") + "@" + address.getAddress().toString().substring(1)).getBytes("UTF-8"));
                    System.out.println(System.getProperty("user.name") + "@" + address.getAddress().toString().substring(1));
                    InetSocketAddress portISA = new InetSocketAddress(address.getBroadcast().toString().substring(1), PORT);
                    sendCh.send(sendBuf, portISA);
                    sendBuf.clear();
                    try { Thread.sleep(500); } catch (InterruptedException e) { }
                }

                try { Thread.sleep(3000); } catch (InterruptedException e) { }
            }
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }
        //sendCh.close();
        //System.out.println("sendCh close");
    }

    public static void receiverProc()
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
                        try { Thread.sleep(timeout * 1000); }
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
                recvBuf.flip();
                int limit = recvBuf.limit();
                String recvStr = new String(recvBuf.array(), recvBuf.position(), limit, "UTF-8");
                if (recvStr.startsWith("ROs#"))
                {
                    String hostname = recvStr.replaceFirst("^ROs#", "");
                    String address = recvStr.replaceFirst("^ROs#.*@", "");

                    try
                    {
                        // control sender
                        SocketChannel channel = SocketChannel.open();
                        channel.connect(new InetSocketAddress(address, SENDER_CONTROL_PORT));
                        channel.write(ByteBuffer.wrap("ROc".getBytes()));
                        channel.close();
                    }
                    catch (IOException e)
                    {
                        continue;
                    }

                    System.out.println(hostname);
                    recvBuf.clear();
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

    private static ArrayList<InterfaceAddress> getBroadcastAddress()
    {
        ArrayList<InterfaceAddress> addressList = new ArrayList<>();

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
                                String address = broadcastAddress.toString().substring(1);
                                if (! address.equals("0.0.0.0"))
                                {
                                    addressList.add(interfaceAddress);
                                }
                            }
                        }
                    }
                }
            }
        }
        catch (SocketException e)
        {
        }

        return addressList;
    }
}

