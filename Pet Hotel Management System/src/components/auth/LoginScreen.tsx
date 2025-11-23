import { useState } from 'react';
import { User, UserRole } from '../../App';
import { Button } from '../ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../ui/card';
import { PawPrint } from 'lucide-react';

interface LoginScreenProps {
  onLogin: (user: User) => void;
}

const mockUsers: User[] = [
  { id: '1', name: 'Sarah Pemilik', email: 'sarah@example.com', role: 'owner' },
  { id: '2', name: 'Admin Hotel', email: 'admin@pethotel.com', role: 'admin' },
  { id: '3', name: 'Budi Perawat', email: 'budi@pethotel.com', role: 'caretaker' },
  { id: '4', name: 'Dr. Rina', email: 'rina@pethotel.com', role: 'vet' },
];

export function LoginScreen({ onLogin }: LoginScreenProps) {
  const [selectedRole, setSelectedRole] = useState<UserRole>('owner');

  const handleQuickLogin = (role: UserRole) => {
    const user = mockUsers.find(u => u.role === role);
    if (user) {
      onLogin(user);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-green-50 to-blue-50 flex items-center justify-center p-4">
      <Card className="w-full max-w-md shadow-xl">
        <CardHeader className="text-center space-y-4">
          <div className="mx-auto w-16 h-16 bg-gradient-to-br from-blue-400 to-green-400 rounded-full flex items-center justify-center">
            <PawPrint className="w-8 h-8 text-white" />
          </div>
          <CardTitle>Pet Hotel Management</CardTitle>
          <CardDescription>
            Sistem manajemen hotel hewan peliharaan yang komprehensif
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="space-y-2">
            <p className="text-center text-sm text-gray-600">
              Demo Login - Pilih Role:
            </p>
            <div className="grid grid-cols-1 gap-2">
              <Button 
                onClick={() => handleQuickLogin('owner')}
                variant={selectedRole === 'owner' ? 'default' : 'outline'}
                className="w-full justify-start"
              >
                <PawPrint className="w-4 h-4 mr-2" />
                Pemilik Hewan
              </Button>
              <Button 
                onClick={() => handleQuickLogin('admin')}
                variant={selectedRole === 'admin' ? 'default' : 'outline'}
                className="w-full justify-start"
              >
                Admin Pet Hotel
              </Button>
              <Button 
                onClick={() => handleQuickLogin('caretaker')}
                variant={selectedRole === 'caretaker' ? 'default' : 'outline'}
                className="w-full justify-start"
              >
                Staf Perawatan
              </Button>
              <Button 
                onClick={() => handleQuickLogin('vet')}
                variant={selectedRole === 'vet' ? 'default' : 'outline'}
                className="w-full justify-start"
              >
                Dokter Hewan
              </Button>
            </div>
          </div>
          <div className="text-center text-xs text-gray-500 pt-4">
            Demo mode - Klik role untuk masuk langsung
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
