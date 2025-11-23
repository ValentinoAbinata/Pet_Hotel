import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Badge } from '../ui/badge';
import { Settings, Users, Bell, FileText, Shield, Book } from 'lucide-react';

export function SettingsView() {
  return (
    <div className="p-4 lg:p-8 space-y-6">
      <div>
        <h1 className="text-2xl lg:text-3xl mb-2">Pengaturan Sistem</h1>
        <p className="text-gray-600">Kelola pengaturan dan konfigurasi Pet Hotel</p>
      </div>

      {/* Staff Management */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Users className="w-5 h-5" />
            Manajemen Staf
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="space-y-3">
            {[
              { name: 'Admin Hotel', email: 'admin@pethotel.com', role: 'Administrator', status: 'Aktif' },
              { name: 'Dr. Rina', email: 'rina@pethotel.com', role: 'Dokter Hewan', status: 'Aktif' },
              { name: 'Budi Perawat', email: 'budi@pethotel.com', role: 'Staf Perawatan', status: 'Aktif' },
              { name: 'Siti Aminah', email: 'siti@pethotel.com', role: 'Staf Perawatan', status: 'Cuti' },
            ].map((staff, index) => (
              <div key={index} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-green-500 rounded-full flex items-center justify-center text-white">
                    {staff.name[0]}
                  </div>
                  <div>
                    <h4>{staff.name}</h4>
                    <p className="text-sm text-gray-600">{staff.email}</p>
                    <p className="text-sm text-gray-500">{staff.role}</p>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <Badge
                    variant="outline"
                    className={
                      staff.status === 'Aktif'
                        ? 'bg-green-50 text-green-700 border-green-200'
                        : 'bg-gray-50 text-gray-700 border-gray-200'
                    }
                  >
                    {staff.status}
                  </Badge>
                  <Button variant="outline" size="sm">Edit</Button>
                </div>
              </div>
            ))}
          </div>
          <Button className="w-full">
            <Users className="w-4 h-4 mr-2" />
            Tambah Staf Baru
          </Button>
        </CardContent>
      </Card>

      {/* System Settings */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Settings className="w-5 h-5" />
              Pengaturan Umum
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="hotelName">Nama Pet Hotel</Label>
              <Input id="hotelName" defaultValue="Happy Paws Pet Hotel" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="hotelEmail">Email Kontak</Label>
              <Input id="hotelEmail" type="email" defaultValue="contact@pethotel.com" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="hotelPhone">Nomor Telepon</Label>
              <Input id="hotelPhone" defaultValue="+62 812 3456 7890" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="hotelAddress">Alamat</Label>
              <Input id="hotelAddress" defaultValue="Jl. Cinta Hewan No. 123, Jakarta" />
            </div>
            <Button className="w-full">Simpan Perubahan</Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Bell className="w-5 h-5" />
              Pengaturan Notifikasi
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            {[
              { label: 'Email Notifikasi Reservasi Baru', enabled: true },
              { label: 'SMS Pengingat Check-in', enabled: true },
              { label: 'Notifikasi Status Kesehatan', enabled: true },
              { label: 'Laporan Harian Otomatis', enabled: false },
              { label: 'Notifikasi Pembayaran', enabled: true },
            ].map((setting, index) => (
              <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span className="text-sm">{setting.label}</span>
                <div
                  className={`w-12 h-6 rounded-full transition-colors cursor-pointer ${
                    setting.enabled ? 'bg-blue-600' : 'bg-gray-300'
                  }`}
                >
                  <div
                    className={`w-5 h-5 bg-white rounded-full mt-0.5 transition-transform ${
                      setting.enabled ? 'ml-6' : 'ml-0.5'
                    }`}
                  />
                </div>
              </div>
            ))}
          </CardContent>
        </Card>
      </div>

      {/* Room Management */}
      <Card>
        <CardHeader>
          <CardTitle>Manajemen Kamar</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {[
              { type: 'Standard', total: 20, occupied: 15, price: 'Rp 100.000' },
              { type: 'Deluxe', total: 15, occupied: 12, price: 'Rp 200.000' },
              { type: 'Suite', total: 10, occupied: 9, price: 'Rp 350.000' },
            ].map((room, index) => (
              <div key={index} className="p-4 border rounded-lg">
                <h4 className="mb-2">{room.type} Room</h4>
                <div className="space-y-2 text-sm">
                  <div className="flex justify-between">
                    <span className="text-gray-600">Total Kamar:</span>
                    <span>{room.total}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-gray-600">Terisi:</span>
                    <span>{room.occupied}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-gray-600">Tersedia:</span>
                    <span>{room.total - room.occupied}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-gray-600">Harga/malam:</span>
                    <span>{room.price}</span>
                  </div>
                </div>
                <Button variant="outline" size="sm" className="w-full mt-3">
                  Edit
                </Button>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>

      {/* Documentation & Training */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Book className="w-5 h-5" />
            Dokumentasi & Pelatihan
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {[
              { title: 'Panduan Penggunaan Sistem', type: 'PDF', size: '2.5 MB', icon: FileText },
              { title: 'Prosedur Check-in/Check-out', type: 'PDF', size: '1.8 MB', icon: FileText },
              { title: 'Panduan Perawatan Hewan', type: 'Video', size: '45 MB', icon: FileText },
              { title: 'Protokol Kesehatan & Keselamatan', type: 'PDF', size: '3.2 MB', icon: Shield },
            ].map((doc, index) => {
              const Icon = doc.icon;
              return (
                <div key={index} className="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                  <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <Icon className="w-6 h-6 text-blue-600" />
                  </div>
                  <div className="flex-1">
                    <h4 className="text-sm">{doc.title}</h4>
                    <p className="text-xs text-gray-600">
                      {doc.type} • {doc.size}
                    </p>
                  </div>
                  <Button variant="outline" size="sm">
                    Lihat
                  </Button>
                </div>
              );
            })}
          </div>
          <Button className="w-full mt-4">
            <FileText className="w-4 h-4 mr-2" />
            Upload Dokumen Baru
          </Button>
        </CardContent>
      </Card>

      {/* Security Settings */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Shield className="w-5 h-5" />
            Keamanan
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span>Autentikasi Dua Faktor</span>
            <Badge className="bg-green-100 text-green-700 hover:bg-green-100">Aktif</Badge>
          </div>
          <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span>Log Aktivitas</span>
            <Button variant="outline" size="sm">Lihat Log</Button>
          </div>
          <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span>Backup Database</span>
            <Button variant="outline" size="sm">Backup Sekarang</Button>
          </div>
          <Button variant="outline" className="w-full">
            Ubah Password
          </Button>
        </CardContent>
      </Card>
    </div>
  );
}
