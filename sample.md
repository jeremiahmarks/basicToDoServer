#Part 4 - notes
from http://krondo.com/blog/?p=1445

**Note:  Do not use any of this stuff in actual production code.**

**It uses low level API stuff that simply should not be used in production**

Notes regarding twisted-client-1/get-poetry.py

* Creates poetrySocket object
* this is basically just a fancy socket connection to an address
* the interesting parts of its __init__ statement:  

=============  
    self.address = address
    self.sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    self.sock.connect(address)
    self.sock.setblocking(0)
* It basically access the socket, connects to the remote address and then turns blocking off
* Finally the init statement imports the reactor and adds itself to the reactor using reactor.addReader(self)

##Interfaces

* principle purposes of Interfaces is documentation

**Note:  Duck Typing:**

> the type of an object is principally defined not by its position in a class hierarchy but by the public interface it presents to the world. Thus two objects which present the same public interface (i.e., walk like a duck, quack like a …) are, as far as duck typing is concerned, the same sort of thing (a duck!).

* we say that a class implements an interface and instances of that class provide the interface 

The addReader method does not have a self parameter. because
it is solely concerned with defining a public interface, and 
the self argument is part of the implementation. **Interface
objects are never instantiated or used as base classes for real
implementations**

IReadDescriptor.doRead()  
- reads data from the socket asynchronously when called from reactor

**doRead is really a callback, but instead of passing it 
directly to Twisted, we pass in an object with a doRead 
method**

**Common in twisted -  instead of passing a function you 
pass an object that must provide a given Interface.**


=========  

One part of the original code for this part that I scratched
my head about several times is this little bit:  

    def parse_args():
        parser = optparse.OptionParser(usage)
        _, addresses = parser.parse_args()
        if not addresses:
            print parser.format_help()
            parser.exit()
        
        def parse_address(addr):
            if ':' not in addr:
                host = '127.0.0.1'
                port = addr
            else:
                host, port = addr.split(':', 1)
            if not port.isdigit():
                parser.error('Ports must be integers.')
            return host, int(port)
        return map(parse_address, addresses)

Specifically it is this part that was throwing me:

    def parse_args():
        def parse_address(addr):

This was blowing my mind until I finally noticed the `map` 
call that is made at the end of the function.  I did not 
know that you could create nested named functions like that.

I just thought it was interesting, so I wanted to record it. 