import { useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Calendar, DollarSign, Home } from 'lucide-react';
import { Badge } from '../ui/badge';

const roomTypes = [
  { id: 'standard', name: 'Standard Room', price: 100000, features: ['AC', 'Tempat Tidur', 'Mainan'] },
  { id: 'deluxe', name: 'Deluxe Room', price: 200000, features: ['AC', 'Tempat Tidur Premium', 'Mainan', 'Kamera 24/7'] },
  { id: 'suite', name: 'Suite Room', price: 350000, features: ['AC', 'Kamar Luas', 'Mainan Premium', 'Kamera 24/7', 'Play Area'] },
];

const services = [
  { id: 'grooming', name: 'Grooming', price: 150000 },
  { id: 'bath', name: 'Mandi', price: 75000 },
  { id: 'vaccine', name: 'Vaksinasi', price: 200000 },
  { id: 'health-check', name: 'Cek Kesehatan', price: 100000 },
];

export function ReservationForm() {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState({
    petName: '',
    petType: 'dog',
    checkIn: '',
    checkOut: '',
    roomType: 'standard',
    services: [] as string[],
  });

  const calculateTotal = () => {
    const room = roomTypes.find(r => r.id === formData.roomType);
    const selectedServices = services.filter(s => formData.services.includes(s.id));
    const serviceTotal = selectedServices.reduce((sum, s) => sum + s.price, 0);
    
    // Calculate days
    if (formData.checkIn && formData.checkOut) {
      const days = Math.ceil(
        (new Date(formData.checkOut).getTime() - new Date(formData.checkIn).getTime()) / 
        (1000 * 60 * 60 * 24)
      );
      return (room?.price || 0) * days + serviceTotal;
    }
    return 0;
  };

  const handleServiceToggle = (serviceId: string) => {
    setFormData(prev => ({
      ...prev,
      services: prev.services.includes(serviceId)
        ? prev.services.filter(s => s !== serviceId)
        : [...prev.services, serviceId]
    }));
  };

  const handleSubmit = () => {
    alert('Reservasi berhasil dibuat! Anda akan menerima konfirmasi melalui email.');
    // Reset form
    setFormData({
      petName: '',
      petType: 'dog',
      checkIn: '',
      checkOut: '',
      roomType: 'standard',
      services: [],
    });
    setStep(1);
  };

  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Buat Reservasi Baru</h1>
        <p className="text-gray-600">
          Isi form di bawah untuk membuat reservasi penitipan hewan peliharaan
        </p>
      </div>

      {/* Progress Indicator */}
      <div className="flex items-center justify-center gap-2">
        {[1, 2, 3].map((s) => (
          <div key={s} className="flex items-center">
            <div
              className={`w-8 h-8 rounded-full flex items-center justify-center ${
                step >= s ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'
              }`}
            >
              {s}
            </div>
            {s < 3 && (
              <div
                className={`w-12 lg:w-24 h-1 ${
                  step > s ? 'bg-blue-600' : 'bg-gray-200'
                }`}
              />
            )}
          </div>
        ))}
      </div>

      {/* Step 1: Pet Info & Dates */}
      {step === 1 && (
        <Card>
          <CardHeader>
            <CardTitle>Informasi Hewan & Tanggal</CardTitle>
            <CardDescription>Masukkan detail hewan peliharaan dan periode menginap</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="petName">Nama Hewan Peliharaan</Label>
              <Input
                id="petName"
                placeholder="Contoh: Luna"
                value={formData.petName}
                onChange={(e) => setFormData({ ...formData, petName: e.target.value })}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="petType">Jenis Hewan</Label>
              <select
                id="petType"
                className="w-full px-3 py-2 border rounded-lg"
                value={formData.petType}
                onChange={(e) => setFormData({ ...formData, petType: e.target.value })}
              >
                <option value="dog">Anjing</option>
                <option value="cat">Kucing</option>
                <option value="rabbit">Kelinci</option>
                <option value="bird">Burung</option>
              </select>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="checkIn">Tanggal Check-in</Label>
                <Input
                  id="checkIn"
                  type="date"
                  value={formData.checkIn}
                  onChange={(e) => setFormData({ ...formData, checkIn: e.target.value })}
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="checkOut">Tanggal Check-out</Label>
                <Input
                  id="checkOut"
                  type="date"
                  value={formData.checkOut}
                  onChange={(e) => setFormData({ ...formData, checkOut: e.target.value })}
                />
              </div>
            </div>

            <Button onClick={() => setStep(2)} className="w-full" disabled={!formData.petName || !formData.checkIn || !formData.checkOut}>
              Lanjutkan
            </Button>
          </CardContent>
        </Card>
      )}

      {/* Step 2: Room Selection */}
      {step === 2 && (
        <Card>
          <CardHeader>
            <CardTitle>Pilih Tipe Kamar</CardTitle>
            <CardDescription>Pilih kamar yang sesuai untuk hewan peliharaan Anda</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="grid grid-cols-1 gap-4">
              {roomTypes.map((room) => (
                <div
                  key={room.id}
                  onClick={() => setFormData({ ...formData, roomType: room.id })}
                  className={`p-4 border-2 rounded-xl cursor-pointer transition-all ${
                    formData.roomType === room.id
                      ? 'border-blue-600 bg-blue-50'
                      : 'border-gray-200 hover:border-gray-300'
                  }`}
                >
                  <div className="flex items-start justify-between mb-2">
                    <div className="flex items-center gap-2">
                      <Home className="w-5 h-5 text-blue-600" />
                      <h3>{room.name}</h3>
                    </div>
                    <div className="text-right">
                      <div className="text-lg text-blue-600">
                        Rp {room.price.toLocaleString('id-ID')}
                      </div>
                      <div className="text-xs text-gray-600">per malam</div>
                    </div>
                  </div>
                  <div className="flex flex-wrap gap-2 mt-3">
                    {room.features.map((feature) => (
                      <Badge key={feature} variant="outline">
                        {feature}
                      </Badge>
                    ))}
                  </div>
                </div>
              ))}
            </div>

            <div className="flex gap-2">
              <Button variant="outline" onClick={() => setStep(1)} className="flex-1">
                Kembali
              </Button>
              <Button onClick={() => setStep(3)} className="flex-1">
                Lanjutkan
              </Button>
            </div>
          </CardContent>
        </Card>
      )}

      {/* Step 3: Additional Services & Confirmation */}
      {step === 3 && (
        <div className="space-y-4">
          <Card>
            <CardHeader>
              <CardTitle>Layanan Tambahan</CardTitle>
              <CardDescription>Pilih layanan tambahan yang Anda butuhkan (opsional)</CardDescription>
            </CardHeader>
            <CardContent className="space-y-3">
              {services.map((service) => (
                <div
                  key={service.id}
                  onClick={() => handleServiceToggle(service.id)}
                  className={`p-4 border-2 rounded-lg cursor-pointer transition-all ${
                    formData.services.includes(service.id)
                      ? 'border-blue-600 bg-blue-50'
                      : 'border-gray-200 hover:border-gray-300'
                  }`}
                >
                  <div className="flex items-center justify-between">
                    <div>
                      <h4>{service.name}</h4>
                      <p className="text-sm text-gray-600">
                        Rp {service.price.toLocaleString('id-ID')}
                      </p>
                    </div>
                    <div className={`w-6 h-6 rounded-full border-2 ${
                      formData.services.includes(service.id)
                        ? 'border-blue-600 bg-blue-600'
                        : 'border-gray-300'
                    }`}>
                      {formData.services.includes(service.id) && (
                        <svg className="w-full h-full text-white" fill="currentColor" viewBox="0 0 20 20">
                          <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                        </svg>
                      )}
                    </div>
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>

          <Card className="bg-gradient-to-br from-blue-50 to-green-50">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <DollarSign className="w-5 h-5" />
                Ringkasan Pembayaran
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex justify-between">
                <span className="text-gray-600">Nama Hewan:</span>
                <span>{formData.petName}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Periode:</span>
                <span>{formData.checkIn} s/d {formData.checkOut}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Tipe Kamar:</span>
                <span>{roomTypes.find(r => r.id === formData.roomType)?.name}</span>
              </div>
              {formData.services.length > 0 && (
                <div className="flex justify-between">
                  <span className="text-gray-600">Layanan Tambahan:</span>
                  <span>{formData.services.length} layanan</span>
                </div>
              )}
              <div className="border-t pt-3 flex justify-between text-lg">
                <span>Total:</span>
                <span className="text-blue-600">
                  Rp {calculateTotal().toLocaleString('id-ID')}
                </span>
              </div>
            </CardContent>
          </Card>

          <div className="flex gap-2">
            <Button variant="outline" onClick={() => setStep(2)} className="flex-1">
              Kembali
            </Button>
            <Button onClick={handleSubmit} className="flex-1">
              Konfirmasi Reservasi
            </Button>
          </div>
        </div>
      )}
    </div>
  );
}
