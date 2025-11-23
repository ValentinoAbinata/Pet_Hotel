import { useState } from 'react';
import { LoginScreen } from './components/auth/LoginScreen';
import { OwnerDashboard } from './components/owner/OwnerDashboard';
import { StaffDashboard } from './components/staff/StaffDashboard';

export type UserRole = 'owner' | 'admin' | 'caretaker' | 'vet';

export interface User {
  id: string;
  name: string;
  email: string;
  role: UserRole;
}

export default function App() {
  const [user, setUser] = useState<User | null>(null);

  const handleLogin = (userData: User) => {
    setUser(userData);
  };

  const handleLogout = () => {
    setUser(null);
  };

  if (!user) {
    return <LoginScreen onLogin={handleLogin} />;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {user.role === 'owner' ? (
        <OwnerDashboard user={user} onLogout={handleLogout} />
      ) : (
        <StaffDashboard user={user} onLogout={handleLogout} />
      )}
    </div>
  );
}
