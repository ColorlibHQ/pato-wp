import sys, itertools
def lum(h):
    h=h.lstrip('#'); r,g,b=[int(h[i:i+2],16)/255 for i in (0,2,4)]
    f=lambda c: c/12.92 if c<=0.03928 else ((c+0.055)/1.055)**2.4
    r,g,b=f(r),f(g),f(b); return 0.2126*r+0.7152*g+0.0722*b
def ratio(a,b):
    la,lb=lum(a),lum(b); hi,lo=max(la,lb),min(la,lb); return (hi+0.05)/(lo+0.05)
if __name__=="__main__":
    pairs=sys.argv[1:]
    for p in pairs:
        a,b=p.split(":")
        r=ratio(a,b)
        tag="AAA" if r>=7 else ("AA" if r>=4.5 else ("AA-large" if r>=3 else "FAIL"))
        print(f"{a} on {b}: {r:5.2f}  {tag}")
