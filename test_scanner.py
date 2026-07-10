from pyzkfp import ZKFP2

zkfp2 = ZKFP2()
zkfp2.Init()

count = zkfp2.GetDeviceCount()
print(f"Devices found: {count}")

if count > 0:
    zkfp2.OpenDevice(0)
    print("Scanner opened successfully!")
    print("Place finger on scanner...")
    
    while True:
        capture = zkfp2.AcquireFingerprint()
        if capture:
            tmp, img = capture
            print(f"Fingerprint captured! Template size: {len(tmp)} bytes")
            break
    
    zkfp2.CloseDevice(0)
    print("Done!")
else:
    print("No scanner found. Make sure Live10R is plugged in.")

zkfp2.Terminate()